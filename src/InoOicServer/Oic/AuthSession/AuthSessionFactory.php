<?php

namespace InoOicServer\Oic\AuthSession;

use InoOicServer\Oic\User;
use InoOicServer\Oic\User\UserInterface;
use InoOicServer\Oic\AbstractSessionFactory;
use InoOicServer\Oic\AuthSession\Hash\AuthSessionHashGeneratorInterface;


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
        
        $authSessionId = $this->getHashGenerator()->generateAuthSessionHash($authStatus, $salt);
        
        $authSession = new AuthSession();
        $authSession->setId($authSessionId);
        $authSession->setMethod($authStatus->getMethod());
        
        $authSession->setCreateTime($createTime);
        $authSession->setExpirationTime($this->getDateTimeUtil()
            ->createExpireDateTime($createTime, 'PT' . $age . 'S'));
        $authSession->setUser($user);
        
        return $authSession;
    }
}