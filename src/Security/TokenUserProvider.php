<?php

namespace App\Security;

use App\Entity\Token;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Exception\FeatureNotImplementedException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class TokenUserProvider implements UserProviderInterface
{
    /**
     * The doctrine entity manager
     *
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * Initialise dependent services
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getUserForToken($tokenCode)
    {
        // First, get the token
        /** @var Token $token */
        $token = $this->entityManager->getRepository('App:Token')->findOneByCode($tokenCode);

        // No token found
        if (!$token) return null;

        // Token is expired or disabled
        if (!$token->isValid()) return null;

        // Token is fine
        return $token->getUser();
    }

    public function loadUserByUsername($username)
    {
        throw new UnsupportedUserException();
    }

    public function refreshUser(UserInterface $user)
    {
        return $this->entityManager->getRepository('App:User')->find($user->getId());
    }

    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
