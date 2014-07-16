<?php

namespace InoOicServer\Oic\AuthSession;

use InoOicServer\Oic\User;
use InoOicServer\Oic\User\UserInterface;
use InoOicServer\Oic\AbstractSessionFactory;
use InoOicServer\Oic\AuthSession\Hash\AuthSessionHashGeneratorInterface;
use InoOicServer\Oic\AuthSession\Hash\AuthSessionHashGenerator;
use InoOicServer\Oic\EntityFactoryInterface;


class AuthSessionFactory extends AbstractSessionFactory implements AuthSessionFactoryInterface
{

    /**
     * @var AuthSessionHashGeneratorInterface
     */
    protected $hashGenerator;


    /**
     * @return AuthSessionHashGeneratorInterface
     */
    public function getHashGenerator()
    {
        if (! $this->hashGenerator instanceof AuthSessionHashGeneratorInterface) {
            $this->hashGenerator = new AuthSessionHashGenerator();
        }
        
        return $this->hashGenerator;
    }


    /**
     * @param AuthSessionHashGeneratorInterface $hashGenerator
     */
    public function setHashGenerator(AuthSessionHashGeneratorInterface $hashGenerator)
    {
        $this->hashGenerator = $hashGenerator;
    }


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\AuthSession\AuthSessionFactoryInterface::createAuthSession()
     */
    public function createAuthSession(User\Authentication\Status $authStatus, $age, $salt)
    {
        if (! $authStatus->isAuthenticated()) {
            throw new Exception\UnauthenticatedUserException('Unauthenticated user - cannot create auth session');
        }
        
        $user = $authStatus->getIdentity();
        if (! $user instanceof UserInterface) {
            throw new Exception\UnknownIdentityException('Missing user identity in authentication status');
        }
        
        $createTime = $authStatus->getTime();
        $expirationTime = $this->getDateTimeUtil()->createExpireDateTime($createTime, 'PT' . $age . 'S');
        
        $authSessionId = $this->getHashGenerator()->generateAuthSessionHash($authStatus, $salt);
        
        $authSessionData = array(
            'id' => $authSessionId,
            'method' => $authStatus->getMethod(),
            'create_time' => $createTime,
            'expiration_time' => $expirationTime,
            'user' => $user
        );
        
        return $this->createEntityFromData($authSessionData);
    }


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\EntityFactoryInterface::createEmptyEntity()
     */
    public function createEmptyEntity()
    {
        return new AuthSession();
    }
}