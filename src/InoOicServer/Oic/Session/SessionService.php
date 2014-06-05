<?php

namespace InoOicServer\Oic\Session;

use DateTime;
use Zend\Stdlib\Hydrator\HydratorInterface;
use InoOicServer\Util\OptionsTrait;
use InoOicServer\Oic\Authorize\Request\Request;
use InoOicServer\Oic\AccessToken\AccessToken;
use InoOicServer\Oic\AuthCode\AuthCode;
use InoOicServer\Oic\User;
use InoOicServer\Oic\Session\Mapper\MapperInterface;
use InoOicServer\Crypto\Hash\HashGeneratorInterface;
use InoOicServer\Crypto\Hash\SimpleHashGenerator;


/**
 * OIC session service.
 * 
 * Available options:
 * - "session_age" (string, DateInterval compatible) - period of time, the session will be valid
 * - "auth_session_salt" (string) - a secret string to be used as a salt in the default token generator. 
 */
class SessionService
{
    
    use OptionsTrait;

    const OPT_SESSION_AGE = 'session_age';

    const OPT_AUTH_SESSION_SALT = 'auth_session_salt';

    /**
     * @var SessionFactoryInterface
     */
    protected $sessionFactory;

    /**
     * @var MapperInterface
     */
    protected $sessionMapper;

    /**
     * @var HydratorInterface
     */
    protected $sessionHydrator;

    /**
     * @var HashGeneratorInterface
     */
    protected $hashGenerator;

    /**
     * @var array
     */
    protected $defaultOptions = array(
        self::OPT_SESSION_AGE => 'PT1H',
        self::OPT_AUTH_SESSION_SALT => 'secret auth session salt - CHANGE IT!'
    );


    /**
     * Constructor.
     * 
     * @param MapperInterface $sessionMapper
     * @param array $options
     */
    public function __construct(MapperInterface $sessionMapper, array $options = array())
    {
        $this->setSessionMapper($sessionMapper);
        $this->setOptions($options);
    }


    /**
     * @return SessionFactoryInterface
     */
    public function getSessionFactory()
    {
        if (! $this->sessionFactory instanceof SessionFactoryInterface) {
            $this->sessionFactory = new SessionFactory();
        }
        
        return $this->sessionFactory;
    }


    /**
     * @param SessionFactoryInterface $sessionFactory
     */
    public function setSessionFactory(SessionFactoryInterface $sessionFactory)
    {
        $this->sessionFactory = $sessionFactory;
    }


    /**
     * @return MapperInterface
     */
    public function getSessionMapper()
    {
        return $this->sessionMapper;
    }


    /**
     * @param MapperInterface $sessionMapper
     */
    public function setSessionMapper(MapperInterface $sessionMapper)
    {
        $this->sessionMapper = $sessionMapper;
    }


    /**
     * @return HydratorInterface
     */
    public function getSessionHydrator()
    {
        if (! $this->sessionHydrator instanceof HydratorInterface) {
            $this->sessionHydrator = new SessionHydrator();
        }
        
        return $this->sessionHydrator;
    }


    /**
     * @param HydratorInterface $sessionHydrator
     */
    public function setSessionHydrator(HydratorInterface $sessionHydrator)
    {
        $this->sessionHydrator = $sessionHydrator;
    }


    /**
     * @return HashGeneratorInterface
     */
    public function getHashGenerator()
    {
        if (! $this->hashGenerator instanceof HashGeneratorInterface) {
            $this->hashGenerator = new SimpleHashGenerator(array(
                SimpleHashGenerator::OPT_SECRET_SALT => $this->getOption(self::OPT_AUTH_SESSION_SALT)
            ));
        }
        
        return $this->hashGenerator;
    }


    /**
     * @param HashGeneratorInterface $hashGenerator
     */
    public function setHashGenerator(HashGeneratorInterface $hashGenerator)
    {
        $this->hashGenerator = $hashGenerator;
    }


    /**
     * Creates an OIC session based on information from the user authentication status.
     * 
     * @param User\Authentication\Status $userAuthStatus
     * @param DateTime $createTime
     * @return Session
     */
    public function createSession(User\Authentication\Status $userAuthStatus, DateTime $createTime = null)
    {
        if (! $userAuthStatus->isAuthenticated()) {
            throw new Exception\InvalidUserAuthenticationStatusException('Cannot create session for unauthenticated user');
        }
        
        $user = $userAuthStatus->getIdentity();
        if (! $user) {
            throw new Exception\InvalidUserAuthenticationStatusException('User identity not found');
        }
        
        if (null === $createTime) {
            $createTime = new DateTime();
        }
        
        $expirationTime = clone $createTime;
        $expirationTime->add(new \DateInterval($this->getOption(self::OPT_SESSION_AGE)));
        
        $session = $this->getSessionFactory()->createSession();
        
        $sessionData = array(
            'id' => $this->generateSessionId($userAuthStatus),
            'authentication_session_id' => $this->generateAuthSessionId($userAuthStatus),
            'authentication_method' => $userAuthStatus->getMethod(),
            'authentication_time' => $userAuthStatus->getTime(),
            'create_time' => $createTime,
            'modify_time' => clone $createTime,
            'expiration_time' => $expirationTime,
            'user' => $user
        );
        
        $session = $this->getSessionHydrator()->hydrate($sessionData, $session);
        
        return $session;
    }


    public function saveSession(Session $session)
    {
        $this->getSessionMapper()->save($session);
    }


    public function fetchSessionByCode(AuthCode $authCode)
    {
        return $this->getSessionMapper()->fetchByCode($authCode->getCode());
    }


    public function fetchSessionByAccessToken(AccessToken $accessToken)
    {
        return $this->getSessionMapper()->fetchByAccessToken($accessToken->getToken());
    }


    public function fetchSessionByUser(User\User $user)
    {
        return $this->getSessionMapper()->fetchByUserId($user->getId());
    }


    public function fetchSessionByRequest(Request $request)
    {
        return $this->getSessionMapper()->fetchByAuthSessionId($request->getAuthenticationSessionId());
    }


    /**
     * Generates a session ID.
     *
     * @param User\Authentication\Status $userAuthStatus
     * @return string
     */
    protected function generateSessionId(User\Authentication\Status $userAuthStatus)
    {
        return $this->getHashGenerator()->generate(array(
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
        return $this->getHashGenerator()->generate(array(
            'Authentication Session ID',
            $userAuthStatus->getIdentity()
                ->getId()
        ));
    }
}