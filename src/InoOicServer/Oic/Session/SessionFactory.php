<?php
namespace InoOicServer\Oic\Session;

use InoOicServer\Oic\AbstractSessionFactory;
use InoOicServer\Oic\AuthSession\AuthSession;
use InoOicServer\Oic\Session\Hash\SessionHashGeneratorInterface;
use InoOicServer\Oic\Session\Hash\SessionHashGenerator;

/**
 * OIC session factory.
 */
class SessionFactory extends AbstractSessionFactory implements SessionFactoryInterface
{

    /**
     * @var SessionHashGeneratorInterface
     */
    protected $hashGenerator;

    /**
     * @return SessionHashGeneratorInterface
     */
    public function getHashGenerator()
    {
        if (! $this->hashGenerator instanceof SessionHashGeneratorInterface) {
            $this->hashGenerator = new SessionHashGenerator();
        }

        return $this->hashGenerator;
    }

    /**
     * @param SessionHashGeneratorInterface $hashGenerator
     */
    public function setHashGenerator(SessionHashGeneratorInterface $hashGenerator)
    {
        $this->hashGenerator = $hashGenerator;
    }

    /**
     * {@inhertidoc}
     * @see \InoOicServer\Oic\Session\SessionFactoryInterface::createSession()
     */
    public function createSession(AuthSession $authSession, $age, $salt, $nonce = null)
    {
        $dateTimeUtil = $this->getDateTimeUtil();

        $createTime = $dateTimeUtil->createDateTime();
        $expirationTime = $dateTimeUtil->createExpireDateTime($createTime, $age);

        $sessionId = $this->getHashGenerator()->generateSessionHash($authSession, $salt);

        $sessionData = array(
            'id' => $sessionId,
            'auth_session_id' => $authSession->getId(),
            'create_time' => $createTime,
            'modify_time' => clone $createTime,
            'expiration_time' => $expirationTime,
            'nonce' => $nonce
        );

        return $this->createEntityFromData($sessionData);
    }

    /**
     * {@inhertidoc}
     * @see \InoOicServer\Oic\EntityFactoryInterface::createEmptyEntity()
     */
    public function createEmptyEntity()
    {
        return new Session();
    }
}