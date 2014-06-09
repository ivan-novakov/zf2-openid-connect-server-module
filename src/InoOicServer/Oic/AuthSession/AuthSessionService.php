<?php

namespace InoOicServer\Oic\AuthSession;

use InoOicServer\Util\OptionsTrait;
use InoOicServer\Oic\User;
use InoOicServer\Crypto\Hash\HashGeneratorInterface;
use InoOicServer\Oic\AuthSession\Mapper\MapperInterface;


class AuthSessionService implements AuthSessionServiceInterface
{
    
    use OptionsTrait;

    /**
     * Salt to be used in auth session ID generation.
     */
    const OPT_SALT = 'salt';

    /**
     * Session age in seconds.
     */
    const OPT_AGE = 'age';

    /**
     * @var MapperInterface 
     */
    protected $authSessionMapper;

    /**
     * @var AuthSessionFactoryInterface
     */
    protected $authSessionFactory;

    /**
     * @var array
     */
    protected $defaultOptions = array(
        self::OPT_SALT => 'some secret salt - CHANGE IT!',
        self::OPT_AGE => 3600
    );


    /**
     * Constructor.
     * 
     * @param MapperInterface $authSessionMapper
     * @param HashGeneratorInterface $hashGenerator
     */
    public function __construct(MapperInterface $authSessionMapper)
    {
        $this->setAuthSessionMapper($authSessionMapper);
    }


    /**
     * @return MapperInterface
     */
    public function getAuthSessionMapper()
    {
        return $this->authSessionMapper;
    }


    /**
     * @param MapperInterface $authSessionMapper
     */
    public function setAuthSessionMapper(MapperInterface $authSessionMapper)
    {
        $this->authSessionMapper = $authSessionMapper;
    }


    /**
     * @return AuthSessionFactoryInterface
     */
    public function getAuthSessionFactory()
    {
        return $this->authSessionFactory;
    }


    /**
     * @param AuthSessionFactoryInterface $authSessionFactory
     */
    public function setAuthSessionFactory(AuthSessionFactoryInterface $authSessionFactory)
    {
        $this->authSessionFactory = $authSessionFactory;
    }


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\AuthSession\AuthSessionServiceInterface::createSession()
     */
    public function createSession(User\Authentication\Status $authStatus)
    {
        $authSession = $this->getAuthSessionFactory()->createAuthSession($authStatus, $this->getOption(self::OPT_AGE), $this->getOption(self::OPT_SALT));
        // maybe validate session ?
        
        return $authSession;
    }


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\AuthSession\AuthSessionServiceInterface::saveSession()
     */
    public function saveSession(AuthSession $authSession)
    {
        $this->getAuthSessionMapper()->save($authSession);
    }


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\AuthSession\AuthSessionServiceInterface::fetchSession()
     */
    public function fetchSession($id)
    {
        return $this->getAuthSessionMapper()->fetch($id);
    }
}