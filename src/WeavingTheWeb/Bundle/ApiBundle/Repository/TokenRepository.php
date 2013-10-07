<?php

namespace WeavingTheWeb\Bundle\ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;
use WeavingTheWeb\Bundle\ApiBundle\Entity\Token;

/**
 * Class TokenRepository
 * @package WeavingTheWeb\Bundle\UserBundle\Repository
 * @author Thierry Marianne <thierry.marianne@weaving-the-web.org>
 */
class TokenRepository extends EntityRepository
{
    /**
     * @param $properties
     * @return Token
     */
    public function makeToken($properties)
    {
        $token = new Token();

        $now = new \DateTime();
        $token->setCreatedAt($now);
        $token->setUpdatedAt($now);

        $token->setOauthToken($properties['oauth_token']);
        $token->setOauthTokenSecret($properties['oauth_token_secret']);

        return $token;
    }
}