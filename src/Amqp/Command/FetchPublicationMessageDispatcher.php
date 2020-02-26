<?php
declare(strict_types=1);

namespace App\Amqp\Command;

use App\Aggregate\Entity\SavedSearch;
use App\Aggregate\Repository\SavedSearchRepository;
use App\Aggregate\Repository\SearchMatchingStatusRepository;
use App\Amqp\Exception\SkippableOperationException;
use App\Amqp\Exception\UnexpectedOwnershipException;
use App\Api\Entity\Token;
use App\Api\Exception\InvalidSerializedTokenException;
use App\Domain\Collection\PublicationCollectionStrategy;
use App\Domain\Collection\PublicationStrategyInterface;
use App\Infrastructure\DependencyInjection\OwnershipAccessorTrait;
use App\Infrastructure\DependencyInjection\PublicationMessageDispatcherTrait;
use App\Infrastructure\DependencyInjection\TranslatorTrait;
use App\Infrastructure\InputConverter\InputToCollectionStrategy;
use App\Operation\OperationClock;
use App\Twitter\Exception\OverCapacityException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @package App\Amqp\Command
 */
class FetchPublicationMessageDispatcher extends AggregateAwareCommand
{
    private const OPTION_BEFORE                 = PublicationStrategyInterface::RULE_BEFORE;
    private const OPTION_SCREEN_NAME            = PublicationStrategyInterface::RULE_SCREEN_NAME;
    private const OPTION_MEMBER_RESTRICTION     = PublicationStrategyInterface::RULE_MEMBER_RESTRICTION;
    private const OPTION_INCLUDE_OWNER          = PublicationStrategyInterface::RULE_INCLUDE_OWNER;
    private const OPTION_IGNORE_WHISPERS        = PublicationStrategyInterface::RULE_IGNORE_WHISPERS;
    private const OPTION_QUERY_RESTRICTION      = PublicationStrategyInterface::RULE_QUERY_RESTRICTION;
    private const OPTION_PRIORITY_TO_AGGREGATES = PublicationStrategyInterface::RULE_PRIORITY_TO_AGGREGATES;
    private const OPTION_LIST                   = PublicationStrategyInterface::RULE_LIST;
    private const OPTION_LISTS                  = PublicationStrategyInterface::RULE_LISTS;

    private const OPTION_OAUTH_TOKEN  = 'oauth_token';
    private const OPTION_OAUTH_SECRET = 'oauth_secret';

    use OwnershipAccessorTrait;
    use PublicationMessageDispatcherTrait;
    use TranslatorTrait;

    /**
     * @var OperationClock
     */
    public OperationClock $operationClock;

    /**
     * @var SavedSearchRepository
     */
    public SavedSearchRepository $savedSearchRepository;

    /**
     * @var SearchMatchingStatusRepository
     */
    public SearchMatchingStatusRepository $searchMatchingStatusRepository;

    /**
     * @var PublicationCollectionStrategy
     */
    private PublicationCollectionStrategy $collectionStrategy;

    public function configure()
    {
        $this->setName('press-review:dispatch-messages-to-fetch-member-statuses')
             ->setDescription('Dispatch messages to fetch member statuses')
             ->addOption(
                 self::OPTION_OAUTH_TOKEN,
                 null,
                 InputOption::VALUE_OPTIONAL,
                 'A token is required'
             )->addOption(
                self::OPTION_OAUTH_SECRET,
                null,
                InputOption::VALUE_OPTIONAL,
                'A secret is required'
            )->addOption(
                self::OPTION_SCREEN_NAME,
                null,
                InputOption::VALUE_REQUIRED,
                'The screen name of a user'
            )->addOption(
                self::OPTION_LIST,
                null,
                InputOption::VALUE_OPTIONAL,
                'A list to which production is restricted to'
            )->addOption(
                self::OPTION_LISTS,
                'l',
                InputOption::VALUE_OPTIONAL,
                'List to which publication of messages is restricted to'
            )
            ->addOption(
                self::OPTION_PRIORITY_TO_AGGREGATES,
                'pa',
                InputOption::VALUE_NONE,
                'Publish messages the priority queue for visible aggregates'
            )
            ->addOption(
                self::OPTION_QUERY_RESTRICTION,
                'qr',
                InputOption::VALUE_OPTIONAL,
                'Query to search statuses against'
            )->addOption(
               self::OPTION_MEMBER_RESTRICTION,
               'mr',
               InputOption::VALUE_OPTIONAL,
               'Restrict to member, which screen name has been passed as value of this option'
            )->addOption(
                self::OPTION_BEFORE,
                null,
                InputOption::VALUE_OPTIONAL,
                'Date before which statuses should have been created'
            )->addOption(
                self::OPTION_INCLUDE_OWNER,
                null,
                InputOption::VALUE_NONE,
                'Should add owner to the list of accounts to be considered'
            )->addOption(
                self::OPTION_IGNORE_WHISPERS,
                'iw',
                InputOption::VALUE_NONE,
                'Should ignore whispers (publication from members having not published anything for a month)'
            )->setAliases(['pr:d-m-t-f-m-s']);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws OptimisticLockException
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input  = $input;
        $this->output = $output;

        $this->collectionStrategy = InputToCollectionStrategy::convertInputToCollectionStrategy($input);

        try {
            $this->setUpDependencies();
        } catch (SkippableOperationException $exception) {
            $this->output->writeln($exception->getMessage());
        } catch (InvalidSerializedTokenException $exception) {
            $this->logger->info($exception->getMessage());

            return self::RETURN_STATUS_FAILURE;
        }

        if ($this->collectionStrategy->shouldSearchByQuery()) {
            $this->produceSearchStatusesMessages();
        }

        if ($this->collectionStrategy->shouldNotSearchByQuery()) {
            $returnStatus = self::RETURN_STATUS_FAILURE;
            try {
                $this->publicationMessageDispatcher->dispatchPublicationMessages(
                    $this->collectionStrategy,
                    Token::fromArray($this->getTokensFromInputOrFallback()),
                    function ($message) {
                        $this->output->writeln($message);
                    }
                );
                $returnStatus = self::RETURN_STATUS_SUCCESS;
            } catch (UnexpectedOwnershipException|OverCapacityException $exception) {
                $this->logger->error($exception->getMessage());
            } catch (Exception $exception) {
                $this->logger->error($exception->getMessage());
            } finally {
                return $returnStatus;
            }
        }

        return self::RETURN_STATUS_SUCCESS;
    }

    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws OptimisticLockException
     */
    private function produceSearchStatusesMessages(): void
    {
        $searchQuery = $this->collectionStrategy->forWhichQuery();

        $savedSearch = $this->savedSearchRepository
            ->findOneBy(['searchQuery' => $searchQuery]);

        if (!($savedSearch instanceof SavedSearch)) {
            $response    = $this->accessor->saveSearch($searchQuery);
            $savedSearch = $this->savedSearchRepository->make($response);
            $this->savedSearchRepository->save($savedSearch);
        }

        $results = $this->accessor->search($savedSearch->searchQuery);

        $this->searchMatchingStatusRepository->saveSearchMatchingStatus(
            $savedSearch,
            $results->statuses,
            $this->accessor->userToken
        );
    }

    /**
     * @throws InvalidSerializedTokenException
     */
    private function setUpDependencies(): void
    {
        if ($this->shouldSkipOperation()) {
            SkippableOperationException::throws('This operation has to be skipped.');
        }

        $this->setUpLogger();

        $this->accessor->setAccessToken(
            Token::fromArray(
                $this->getTokensFromInputOrFallback()
            )
        );

        $this->setupAggregateRepository();

        if (
            $this->collectionStrategy->shouldPrioritizeLists()
            && ($this->collectionStrategy->listRestriction()
                || $this->collectionStrategy->shouldApplyListCollectionRestriction())
        ) {
            // TODO customize message to be dispatched
            // Before introducting messenger component
            // it produced messages with
            // old_sound_rabbit_mq.weaving_the_web_amqp.twitter.aggregates_status_producer
            // old_sound_rabbit_mq.weaving_the_web_amqp.producer.aggregates_likes_producer
            // services
        }

        if ($this->collectionStrategy->shouldSearchByQuery()) {
            // TODO customize message to be dispatched
            // Before introducing messenger component
            // it produced messages with
            // old_sound_rabbit_mq.weaving_the_web_amqp.producer.search_matching_statuses_producer
            // service
        }
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function shouldSkipOperation(): bool
    {
        return $this->operationClock->shouldSkipOperation()
            && $this->collectionStrategy->allListsAreEquivalent()
            && $this->collectionStrategy->noQueryRestriction();
    }
}