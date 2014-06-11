<?php

namespace InoOicServer\Oic\AuthCode;

use InoOicServer\Oic\AbstractSessionFactory;
use InoOicServer\Oic\Session\Session;
use InoOicServer\Oic\Client\Client;
use InoOicServer\Oic\AuthCode\Hash\AuthCodeHashGeneratorInterface;
use InoOicServer\Oic\AuthCode\Hash\AuthCodeHashGenerator;


class AuthCodeFactory extends AbstractSessionFactory implements AuthCodeFactoryInterface
{

    /**
     * @var AuthCodeHashGeneratorInterface
     */
    protected $hashGenerator;


    /**
     * @return AuthCodeHashGeneratorInterface
     */
    public function getHashGenerator()
    {
        if (! $this->hashGenerator instanceof AuthCodeHashGeneratorInterface) {
            $this->hashGenerator = new AuthCodeHashGenerator();
        }
        
        return $this->hashGenerator;
    }


    /**
     * @param AuthCodeHashGeneratorInterface $hashGenerator
     */
    public function setHashGenerator(AuthCodeHashGeneratorInterface $hashGenerator)
    {
        $this->hashGenerator = $hashGenerator;
    }


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\AuthCode\AuthCodeFactoryInterface::createAuthCode()
     */
    public function createAuthCode(Session $session, Client $client, $age, $salt, $scope = null)
    {
        $authCode = new AuthCode();
        $authCodeHash = $this->getHashGenerator()->generateAuthCodeHash($session, $salt);
        
        $dateTimeUtil = $this->getDateTimeUtil();
        $createTime = $dateTimeUtil->createDateTime();
        $expirationTime = $dateTimeUtil->createExpireDateTime($createTime, $age);
        
        $data = array(
            'code' => $authCodeHash,
            'client_id' => $client->getId(),
            'session_id' => $session->getId(),
            'create_time' => $createTime,
            'expiration_time' => $expirationTime,
            'scope' => $scope
        );
        
        $authCode = $this->getHydrator()->hydrate($data, $authCode);
        
        return $authCode;
    }
}