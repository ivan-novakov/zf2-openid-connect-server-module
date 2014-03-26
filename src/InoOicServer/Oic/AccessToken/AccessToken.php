<?php

namespace InoOicServer\Oic\AccessToken;

use DateTime;
use InoOicServer\Util\ConvertToDateTimeTrait;


/**
 * The OIC access token entity.
 */
class AccessToken
{
    
    use ConvertToDateTimeTrait;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $sessionId;

    /**
     * @var DateTime
     */
    protected $createTime;

    /**
     * @var DateTime
     */
    protected $expirationTime;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $scope;


    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }


    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }


    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }


    /**
     * @param string $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }


    /**
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }


    /**
     * @param string $sessionId
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
    }


    /**
     * @return DateTime
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }


    /**
     * @param string|DateTime $createTime
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $this->convertToDateTime($createTime);
    }


    /**
     * @return DateTime
     */
    public function getExpirationTime()
    {
        return $this->expirationTime;
    }


    /**
     * @param string|DateTime $expirationTime
     */
    public function setExpirationTime($expirationTime)
    {
        $this->expirationTime = $this->convertToDateTime($expirationTime);
    }


    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }


    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }


    /**
     * @param string $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }
}