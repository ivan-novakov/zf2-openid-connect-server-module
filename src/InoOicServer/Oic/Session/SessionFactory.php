<?php

namespace InoOicServer\Oic\Session;

use DateTime;
use InoOicServer\Oic\User;
use InoOicServer\Oic\Client\Client;
use InoOicServer\Util\OptionsTrait;
use InoOicServer\Util\TokenGenerator\TokenGeneratorInterface;
use InoOicServer\Util\TokenGenerator\Simple;


/**
 * OIC session factory.
 * 
 * Available options:
 * - "session_age" (string, DateInterval compatible) - period of time, the session will be valid
 * - "auth_session_salt" (string) - a secret string to be used as a salt in the default token generator. 
 */
class SessionFactory implements SessionFactoryInterface
{
    use OptionsTrait;

    const OPT_SESSION_AGE = 'session_age';

    const OPT_AUTH_SESSION_SALT = 'auth_session_salt';

    /**
     * @var array
     */
    protected $defaultOptions = array(
        self::OPT_SESSION_AGE => 'PT1H',
        self::OPT_AUTH_SESSION_SALT => 'secret auth session salt - CHANGE IT!'
    );

    /**
     * @var TokenGeneratorInterface
     */
    protected $tokenGenerator;


    /**
     * Constructor.
     * 
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->setOptions($options);
    }


    /**
     * @return TokenGeneratorInterface
     */
    public function getTokenGenerator()
    {
        if (! $this->tokenGenerator instanceof TokenGeneratorInterface) {
            $this->tokenGenerator = new Simple(
                array(
                    Simple::OPT_SECRET_SALT => self::OPT_AUTH_SESSION_SALT
                ));
        }
        return $this->tokenGenerator;
    }


    /**
     * @param TokenGeneratorInterface $tokenGenerator
     */
    public function setTokenGenerator($tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
    }

    
    
    public function createEmptySession()
    {
        return new Session();
    }

    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\Session\SessionFactoryInterface::createInitialSession()
     */
    public function createSession(User\Authentication\Status $userAuthStatus, DateTime $createTime = null)
    {
        /* @var $userAuthStatus \InoOicServer\Oic\User\Authentication\Status */
        if (! $userAuthStatus->isAuthenticated()) {
            throw new Exception\InvalidUserAuthenticationStatusException(
                'Cannot create session for unauthenticated user');
        }
        
        $user = $userAuthStatus->getIdentity();
        if (! $user) {
            throw new Exception\InvalidUserAuthenticationStatusException('User identity not found');
        }
        
        $session = $this->createEmptySession();
        $session->setId($this->generateSessionId($userAuthStatus));
        $session->setAuthenticationSessionId($this->generateAuthSessionId($userAuthStatus));
        
        $session->setAuthenticationMethod($userAuthStatus->getMethod());
        $session->setAuthenticationTime($userAuthStatus->getTime());
        
        if (null === $createTime) {
            $createTime = new DateTime();
        }
        $session->setCreateTime($createTime);
        $session->setModifyTime(clone $createTime);
        
        $expirationTime = clone $createTime;
        $expirationTime->add(new \DateInterval($this->getOption(self::OPT_SESSION_AGE)));
        $session->setExpirationTime($expirationTime);
        
        $session->setUser($user);
        
        return $session;
    }


    /**
     * Generates a session ID.
     * 
     * @param User\Authentication\Status $userAuthStatus
     * @return string
     */
    protected function generateSessionId(User\Authentication\Status $userAuthStatus)
    {
        return $this->getTokenGenerator()->generate(
            array(
                'Session ID',
                $userAuthStatus->getIdentity()
                    ->getId()
            ));
    }


    /**
     * Generates an authentication session ID.
     * 
     * @param User\Authentication\Status $userAuthStatus
     * @return string
     */
    protected function generateAuthSessionId(User\Authentication\Status $userAuthStatus)
    {
        return $this->getTokenGenerator()->generate(
            array(
                'Authentication Session ID',
                $userAuthStatus->getIdentity()
                    ->getId()
            ));
    }
}