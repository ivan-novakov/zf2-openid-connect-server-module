<?php

namespace InoOicServer\Session;

use Zend\Stdlib\Parameters;
use InoOicServer\Session\Token\AccessToken;
use InoOicServer\General\Exception as GeneralException;
use InoOicServer\Session\Token\AuthorizationCode;
use InoOicServer\User\UserInterface;
use InoOicServer\User\Serializer;
use InoOicServer\Client\Client;
use InoOicServer\Authentication;
use InoOicServer\Util;


class SessionManager
{

    const OPT_SESSION_EXPIRE_INTERVAL = 'session_expire_interval';

    const OPT_AUTHORIZATION_CODE_EXPIRE_INTERVAL = 'authorization_code_expire_interval';

    const OPT_ACCESS_TOKEN_EXPIRE_INTERVAL = 'access_token_expire_interval';

    const OPT_REFRESH_TOKEN_EXPIRE_INTERVAL = 'refresh_token_expire_interval';

    /**
     * @var Parameters
     */
    protected $options;

    /**
     * The session storage.
     * 
     * @var Storage\StorageInterface
     */
    protected $storage;

    /**
     * The user serializer object.
     * 
     * @var Serializer\SerializerInterface
     */
    protected $userSerializer;

    /**
     * The session ID generator object.
     * 
     * @var IdGenerator\IdGeneratorInterface
     */
    protected $sessionIdGenerator;

    /**
     * Token generator oibject.
     * 
     * @var Hash\Generator\GeneratorInterface
     */
    protected $tokenGenerator;

    /**
     * @var Util\DateTimeUtil
     */
    protected $dateTimeUtil;


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
     * Sets the options.
     * 
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = new Parameters($options);
    }


    /**
     * Returns the options.
     * 
     * @return Parameters
     */
    public function getOptions()
    {
        return $this->options;
    }


    /**
     * Sets the session storage object.
     *
     * @param Storage\StorageInterface $storage
     */
    public function setStorage(Storage\StorageInterface $storage)
    {
        $this->storage = $storage;
    }


    /**
     * Returns the session storage object.
     *
     * @return Storage\StorageInterface
     */
    public function getStorage()
    {
        return $this->storage;
    }


    /**
     * Sets the session ID generator object.
     *
     * @param IdGenerator\IdGeneratorInterface $idGenerator
     */
    public function setSessionIdGenerator(IdGenerator\IdGeneratorInterface $idGenerator)
    {
        $this->sessionIdGenerator = $idGenerator;
    }


    /**
     * Returns the session ID generator object.
     *
     * @return IdGenerator\IdGeneratorInterface
     */
    public function getSessionIdGenerator()
    {
        return $this->sessionIdGenerator;
    }


    /**
     * Sets the token generator object.
     *
     * @param Hash\Generator\GeneratorInterface
     */
    public function setTokenGenerator(Hash\Generator\GeneratorInterface $generator)
    {
        $this->tokenGenerator = $generator;
    }


    /**
     * Return the token generator object.
     *
     * @return Hash\Generator\GeneratorInterface
     */
    public function getTokenGenerator()
    {
        return $this->tokenGenerator;
    }


    /**
     * Sets the user serializer object.
     *
     * @param Serializer\SerializerInterface $serializer
     */
    public function setUserSerializer(Serializer\SerializerInterface $serializer)
    {
        $this->userSerializer = $serializer;
    }


    /**
     * Returns the user serializer object.
     *
     * @return Serializer\SerializerInterface
     */
    public function getUserSerializer()
    {
        return $this->userSerializer;
    }


    /**
     * @return Util\DateTimeUtil
     */
    public function getDateTimeUtil()
    {
        if (! $this->dateTimeUtil instanceof Util\DateTimeUtil) {
            $this->dateTimeUtil = new Util\DateTimeUtil();
        }
        return $this->dateTimeUtil;
    }


    /**
     * @param Util\DateTimeUtil $dateTimeUtil
     */
    public function setDateTimeUtil(DateTimeUtil $dateTimeUtil)
    {
        $this->dateTimeUtil = $dateTimeUtil;
    }


    /**
     * Extracts user data from the session and returns the user object.
     * 
     * @param Session $session
     * @throws GeneralException\MissingDependencyException
     * @return UserInterface
     */
    public function getUserFromSession(Session $session)
    {
        $serializer = $this->getUserSerializer();
        if (! $serializer) {
            throw new GeneralException\MissingDependencyException('user serializer');
        }
        
        return $serializer->unserialize($session->getUserData());
    }


    /**
     * Creates a new session for the provided user and saves it in the storage.
     * 
     * @param UserInterface $user
     * @param Authentication\Info $authenticationInfo
     * @throws Exception\MissingComponentException
     * @return Session
     */
    public function createSession(UserInterface $user, Authentication\Info $authenticationInfo)
    {
        $sessionIdGenerator = $this->getSessionIdGenerator();
        if (! $sessionIdGenerator) {
            throw new GeneralException\MissingDependencyException('session ID generator');
        }
        
        $serializer = $this->getUserSerializer();
        if (! $serializer) {
            throw new GeneralException\MissingDependencyException('user serializer');
        }
        
        $storage = $this->getStorageWithCheck();
        
        $sessionId = $sessionIdGenerator->generateId(array(
            $user->getId()
        ));
        
        $serializedUserData = $serializer->serialize($user);
        
        $dateTimeUtil = $this->getDateTimeUtil();
        $now = $dateTimeUtil->createDateTime();
        $expire = $dateTimeUtil->createExpireDateTime($now, $this->getSessionExpireInterval());
        
        $session = new Session(
            array(
                Session::FIELD_ID => $sessionId,
                Session::FIELD_USER_ID => $user->getId(),
                Session::FIELD_CREATE_TIME => $now,
                Session::FIELD_MODIFY_TIME => $now,
                Session::FIELD_AUTHENTICATION_TIME => $authenticationInfo->getTime(),
                Session::FIELD_EXPIRATION_TIME => $expire,
                Session::FIELD_AUTHENTICATION_METHOD => $authenticationInfo->getMethod(),
                Session::FIELD_USER_DATA => $serializedUserData
            ));
        
        $storage->saveSession($session);
        
        return $session;
    }


    public function updateSession(Session $session)
    {}


    /**
     * Returns the session associated with the authorization code.
     * 
     * @param AuthorizationCode $authorizationCode
     * @return Session
     */
    public function getSessionForAuthorizationCode(AuthorizationCode $authorizationCode)
    {
        return $this->getStorageWithCheck()->loadSession($authorizationCode->getSessionId());
    }


    /**
     * Returns the corresponding session for the provided access token.
     * 
     * @param AccessToken $accessToken
     * @return Session
     */
    public function getSessionByAccessToken(AccessToken $accessToken)
    {
        return $this->getStorageWithCheck()->loadSession($accessToken->getSessionId());
    }


    /**
     * Creates new authorization code for the provided session, bound to the provided client.
     * 
     * @param Session $session
     * @param Client $client
     * @throws Exception\MissingComponentException
     * @return AuthorizationCode
     */
    public function createAuthorizationCode(Session $session, Client $client)
    {
        $tokenGenerator = $this->getTokenGenerator();
        if (! $tokenGenerator) {
            throw new GeneralException\MissingDependencyException('token generator');
        }
        
        $storage = $this->getStorageWithCheck();
        
        $code = $tokenGenerator->generateAuthorizationCode($session, $client);
        
        $now = $this->getDateTimeUtil()->createDateTime();
        $expire = $this->getDateTimeUtil()->createExpireDateTime($now, $this->getAuthorizationCodeExpireInterval());
        
        $authorizationCode = new AuthorizationCode(
            array(
                AuthorizationCode::FIELD_CODE => $code,
                AuthorizationCode::FIELD_SESSION_ID => $session->getId(),
                AuthorizationCode::FIELD_ISSUE_TIME => $now,
                AuthorizationCode::FIELD_EXPIRATION_TIME => $expire,
                AuthorizationCode::FIELD_CLIENT_ID => $client->getId(),
                AuthorizationCode::FIELD_SCOPE => 'openid'
            ));
        
        $storage->saveAuthorizationCode($authorizationCode);
        
        return $authorizationCode;
    }


    /**
     * Returns the authorization code object for the provided code or NULL if not found.
     * 
     * @param string $code
     * @throws GeneralException\MissingDependencyException
     * @return AuthorizationCode|NULL
     */
    public function getAuthorizationCode($code)
    {
        return $this->getStorageWithCheck()->loadAuthorizationCode($code);
    }


    /**
     * Deactivates the authentication code - it will not be possible to acquire the corresponding session anymore.
     * 
     * @param AuthorizationCode $code
     */
    public function deactivateAuthorizationCode(AuthorizationCode $authorizationCode)
    {
        $this->getStorageWithCheck()->deleteAuthorizationCode($authorizationCode);
    }


    public function createAccessToken(Session $session, Client $client)
    {
        $storage = $this->getStorageWithCheck();
        $generator = $this->getTokenGeneratorWithCheck();
        
        $token = $generator->generateAccessToken($session, $client);
        
        $now = $this->getDateTimeUtil()->createDateTime();
        $expire = $this->getDateTimeUtil()->createExpireDateTime($now, $this->getAccessTokenExpireInterval());
        
        $accessToken = new AccessToken(
            array(
                AccessToken::FIELD_TOKEN => $token,
                AccessToken::FIELD_SESSION_ID => $session->getId(),
                AccessToken::FIELD_CLIENT_ID => $client->getId(),
                AccessToken::FIELD_ISSUE_TIME => $now,
                AccessToken::FIELD_EXPIRATION_TIME => $expire,
                AccessToken::FIELD_TYPE => AccessToken::TYPE_BEARER,
                AccessToken::FIELD_SCOPE => 'openid'
            ));
        
        $storage->saveAccessToken($accessToken);
        
        return $accessToken;
    }


    /**
     * Returns the access token object with the provided code.
     * 
     * @param string $token
     * @return AccessToken
     */
    public function getAccessToken($token)
    {
        return $this->getStorageWithCheck()->loadAccessToken($token);
    }


    public function createRefreshToken()
    {}


    public function getRefreshToken($token)
    {}


    /**
     * Returns the session expire interval.
     * 
     * @return string
     */
    public function getSessionExpireInterval()
    {
        return $this->options->get(self::OPT_SESSION_EXPIRE_INTERVAL, 'PT1H');
    }


    /**
     * Returns the authorization code expire interval.
     * 
     * @return string
     */
    public function getAuthorizationCodeExpireInterval()
    {
        return $this->options->get(self::OPT_AUTHORIZATION_CODE_EXPIRE_INTERVAL, 'PT5M');
    }


    /**
     * Returns the access token expire interval.
     * 
     * @return string
     */
    public function getAccessTokenExpireInterval()
    {
        return $this->options->get(self::OPT_ACCESS_TOKEN_EXPIRE_INTERVAL, 'PT12H');
    }


    /**
     * Returns the refresh token expire interval.
     * 
     * @return string
     */
    public function getRefreshTokenExpireInterval()
    {
        return $this->options->get(self::OPT_REFRESH_TOKEN_EXPIRE_INTERVAL, 'PT24H');
    }


    /**
     * Returns the storage object and throws exception if not set.
     * 
     * @throws Exception\MissingComponentException
     * @return Storage\StorageInterface
     */
    protected function getStorageWithCheck()
    {
        $storage = $this->getStorage();
        if (! $storage) {
            throw new GeneralException\MissingDependencyException('session storage');
        }
        
        return $storage;
    }


    /**
     * Returns the token generator object or throws exception if not set.
     * 
     * @throws GeneralException\MissingDependencyException
     * @return Hash\Generator\GeneratorInterface
     */
    protected function getTokenGeneratorWithCheck()
    {
        $generator = $this->getTokenGenerator();
        if (! $generator) {
            throw new GeneralException\MissingDependencyException('token generator');
        }
        
        return $generator;
    }
}