<?php
namespace InoOicServer\Oic\Session;

use InoOicServer\Util\OptionsTrait;
use InoOicServer\Oic\AccessToken\AccessToken;
use InoOicServer\Oic\AuthCode\AuthCode;
use InoOicServer\Oic\Session\Mapper\MapperInterface;
use InoOicServer\Oic\AuthSession\AuthSession;
use InoOicServer\Oic\User\UserInterface;

/**
 * OIC session service.
 *
 * Available options:
 * - "session_age" (string, DateInterval compatible) - period of time, the session will be valid
 * - "auth_session_salt" (string) - a secret string to be used as a salt in the default token generator.
 */
class SessionService implements SessionServiceInterface
{
    
    use OptionsTrait;

    const OPT_AGE = 'age';

    const OPT_SALT = 'salt';

    /**
     * @var SessionFactoryInterface
     */
    protected $sessionFactory;

    /**
     * @var MapperInterface
     */
    protected $sessionMapper;

    /**
     * @var array
     */
    protected $defaultOptions = array(
        self::OPT_AGE => 'PT1H',
        self::OPT_SALT => 'secret auth session salt - CHANGE IT!'
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
     * {@inheritdoc}
     * @see \InoOicServer\Oic\Session\SessionServiceInterface::createSession()
     */
    public function createSession(AuthSession $authSession, $nonce = null)
    {
        $age = $this->getOption(self::OPT_AGE);
        $salt = $this->getOption(self::OPT_SALT);
        
        $session = $this->getSessionFactory()->createSession($authSession, $age, $salt, $nonce);
        
        return $session;
    }

    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\Session\SessionServiceInterface::saveSession()
     */
    public function saveSession(Session $session)
    {
        $this->getSessionMapper()->save($session);
    }

    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\Session\SessionServiceInterface::fetchSession()
     */
    public function fetchSession($id)
    {
        return $this->getSessionMapper()->fetch($id);
    }

    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\Session\SessionServiceInterface::fetchSessionByCode()
     */
    public function fetchSessionByCode(AuthCode $authCode)
    {
        return $this->getSessionMapper()->fetchByCode($authCode->getCode());
    }

    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\Session\SessionServiceInterface::fetchSessionByAccessToken()
     */
    public function fetchSessionByAccessToken(AccessToken $accessToken)
    {
        return $this->getSessionMapper()->fetchByAccessToken($accessToken->getToken());
    }

    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\Session\SessionServiceInterface::fetchSessionByUser()
     */
    public function fetchSessionByUser(UserInterface $user)
    {
        return $this->getSessionMapper()->fetchByUserId($user->getId());
    }

    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\Session\SessionServiceInterface::fetchSessionByAuthSession()
     */
    public function fetchSessionByAuthSession(AuthSession $authSession)
    {
        return $this->getSessionMapper()->fetchByAuthSessionId($authSession->getId());
    }

    /**
     * {@inhertidoc}
     * @see \InoOicServer\Oic\Session\SessionServiceInterface::initSessionFromAuthSession()
     */
    public function initSessionFromAuthSession(AuthSession $authSession, $nonce = null)
    {
        $session = $this->fetchSessionByAuthSession($authSession);
        if (! $session) {
            $session = $this->createSession($authSession, $nonce);
            $this->saveSession($session);
        }
        
        return $session;
    }
}