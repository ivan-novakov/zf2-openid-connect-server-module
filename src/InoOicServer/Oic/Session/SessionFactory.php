<?php

namespace InoOicServer\Oic\Session;

use InoOicServer\Oic\AbstractSessionFactory;
use InoOicServer\Oic\AuthSession\AuthSession;


/**
 * OIC session factory.
 */
class SessionFactory extends AbstractSessionFactory implements SessionFactoryInterface
{


    /**
     * {@inhertidoc}
     * @see \InoOicServer\Oic\Session\SessionFactoryInterface::createSession()
     */
    public function createSession(AuthSession $authSession, $age, $salt, $nonce = null)
    {
        $dateTimeUtil = $this->getDateTimeUtil();
        
        $createTime = $dateTimeUtil->createDateTime();
        $expirationTime = $dateTimeUtil->createExpireDateTime($createTime, $age);
        
        $session = new Session();
        
        $authSessionId = $authSession->getId();
        $sessionId = $this->getHashGenerator()->generate(array(
            $authSessionId,
            $createTime->getTimestamp(),
            $salt
        ));
        
        $sessionData = array(
            'id' => $sessionId,
            'authentication_session_id' => $authSessionId,
            'create_time' => $createTime,
            'modify_time' => clone $createTime,
            'expiration_time' => $expirationTime,
            'nonce' => $nonce
        );
        
        $session = $this->getHydrator()->hydrate($sessionData, $session);
        
        return $session;
    }
}