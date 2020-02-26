<?php
declare(strict_types=1);

namespace App\Tests\Builder;

use App\Api\AccessToken\Repository\TokenRepositoryInterface;
use Prophecy\Prophet;

class TokenRepositoryBuilder
{
    public static function newTokenRepositoryBuilder()
    {
        return new self();
    }

    private $prophecy;

    public function __construct()
    {
        $prophet = new Prophet();
        $this->prophecy = $prophet->prophesize(TokenRepositoryInterface::class);
    }

    public function build(): TokenRepositoryInterface
    {
        return $this->prophecy->reveal();
    }

    public function willFindATokenOtherThan($excludedToken, $returnedToken)
    {
        $this->prophecy
            ->findTokenOtherThan($excludedToken)
            ->willReturn($returnedToken);

        return $this;
    }

    public function willReturnTheCountOfUnfrozenTokens($count)
    {
        $this->prophecy
            ->howManyUnfrozenTokenAreThere()
            ->willReturn($count);

        return $this;
    }
}