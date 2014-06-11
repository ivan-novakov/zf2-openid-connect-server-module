<?php

namespace InoOicServer\Oic\AuthCode;

use InoOicServer\Oic\Session\Session;
use InoOicServer\Oic\Client\Client;
use InoOicServer\Util\OptionsTrait;


class AuthCodeService implements AuthCodeServiceInterface
{
    
    use OptionsTrait;

    const OPT_AGE = 'age';

    const OPT_SALT = 'salt';

    /**
     * @var Mapper\MapperInterface
     */
    protected $authCodeMapper;

    /**
     * @var AuthCodeFactoryInterface
     */
    protected $authCodeFactory;

    /**
     * @var array
     */
    protected $defaultOptions = array(
        'age' => 3600,
        'salt' => 'auth code default salt - change it'
    );


    /**
     * Constructor.
     * 
     * @param Mapper\MapperInterface $authCodeMapper
     * @param array $options
     */
    public function __construct(Mapper\MapperInterface $authCodeMapper, array $options = array())
    {
        $this->setAuthCodeMapper($authCodeMapper);
        $this->setOptions($options);
    }


    /**
     * @return Mapper\MapperInterface
     */
    public function getAuthCodeMapper()
    {
        return $this->authCodeMapper;
    }


    /**
     * @param Mapper\MapperInterface $authCodeMapper
     */
    public function setAuthCodeMapper(Mapper\MapperInterface $authCodeMapper)
    {
        $this->authCodeMapper = $authCodeMapper;
    }


    /**
     * @return AuthCodeFactoryInterface
     */
    public function getAuthCodeFactory()
    {
        if (! $this->authCodeFactory instanceof AuthCodeFactoryInterface) {
            $this->authCodeFactory = new AuthCodeFactory();
        }
        
        return $this->authCodeFactory;
    }


    /**
     * @param AuthCodeFactoryInterface $authCodeFactory
     */
    public function setAuthCodeFactory(AuthCodeFactoryInterface $authCodeFactory)
    {
        $this->authCodeFactory = $authCodeFactory;
    }


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\AuthCode\AuthCodeServiceInterface::createAuthCode()
     */
    public function createAuthCode(Session $session, Client $client, $scope = null)
    {
        $age = $this->getOption(self::OPT_AGE);
        $salt = $this->getOption(self::OPT_SALT);
        
        $authCode = $this->getAuthCodeFactory()->createAuthCode($session, $client, $age, $salt, $scope);
        
        return $authCode;
    }


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\AuthCode\AuthCodeServiceInterface::fetchAuthCode()
     */
    public function fetchAuthCode($code)
    {
        return $this->getAuthCodeMapper()->fetch($code);
    }


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\AuthCode\AuthCodeServiceInterface::deleteAuthCode()
     */
    public function deleteAuthCode(AuthCode $authCode)
    {
        $this->getAuthCodeMapper()->delete($authCode->getCode());
        
        return true;
    }
}