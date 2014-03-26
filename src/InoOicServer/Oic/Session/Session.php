<?php

namespace InoOicServer\Oic\Session;

use DateTime;
use Zend\Stdlib\ArrayObject;
use InoOicServer\Util\ConvertToDateTimeTrait;


/**
 * The OIC session entity.
 * 
 * Holds user data for a period of time:
 * - user authentication
 * - user data
 * 
 */
class Session
{
    /**
     * Used to convert datetime values in the setters.
     */
    use ConvertToDateTimeTrait;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $authenticationSessionId;

    /**
     * @var string
     */
    protected $userId;

    /**
     * @var DateTime
     */
    protected $createTime;

    /**
     * @var DateTime
     */
    protected $modifyTime;

    /**
     * @var DateTime
     */
    protected $expirationTime;

    /**
     * @var string
     */
    protected $authenticationMethod;

    /**
     * @var DateTime
     */
    protected $authenticationTime;

    /**
     * @var ArrayObject
     */
    protected $userData;


    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * @return string
     */
    public function getAuthenticationSessionId()
    {
        return $this->authenticationSessionId;
    }


    /**
     * @param string $authenticationSessionId
     */
    public function setAuthenticationSessionId($authenticationSessionId)
    {
        $this->authenticationSessionId = $authenticationSessionId;
    }


    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }


    /**
     * @param string $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
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
    public function getModifyTime()
    {
        return $this->modifyTime;
    }


    /**
     * @param string|DateTime $modifyTime
     */
    public function setModifyTime($modifyTime)
    {
        $this->modifyTime = $this->convertToDateTime($modifyTime);
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
    public function getAuthenticationMethod()
    {
        return $this->authenticationMethod;
    }


    /**
     * @param string $authenticationMethod
     */
    public function setAuthenticationMethod($authenticationMethod)
    {
        $this->authenticationMethod = $authenticationMethod;
    }


    /**
     * @return DateTime
     */
    public function getAuthenticationTime()
    {
        return $this->authenticationTime;
    }


    /**
     * @param string|DateTime $authenticationTime
     */
    public function setAuthenticationTime($authenticationTime)
    {
        $this->authenticationTime = $this->convertToDateTime($authenticationTime);
    }


    /**
     * @return ArrayObject
     */
    public function getUserData()
    {
        return $this->userData;
    }


    /**
     * @param \Traversable|ArrayObject $userData
     */
    public function setUserData($userData)
    {
        $this->userData = new ArrayObject($userData);
    }
}