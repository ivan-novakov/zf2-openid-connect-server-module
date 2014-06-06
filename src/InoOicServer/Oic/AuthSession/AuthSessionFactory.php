<?php

namespace InoOicServer\Oic\AuthSession;

use InoOicServer\Oic\User;
use InoOicServer\Oic\User\UserInterface;
use InoOicServer\Crypto\Hash\HashGeneratorInterface;
use InoOicServer\Util\OptionsTrait;
use InoOicServer\Util\DateTimeUtil;
use InoOicServer\Oic\AbstractSessionFactory;


class AuthSessionFactory extends AbstractSessionFactory implements AuthSessionFactoryInterface
{


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
        
        $authSessionId = $this->getHashGenerator()->generate(array(
            $user->getId(),
            $createTime->getTimestamp(),
            $salt
        ));
        
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