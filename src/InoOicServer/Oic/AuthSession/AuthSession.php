<?php

namespace InoOicServer\Oic\AuthSession;

use DateTime;
use InoOicServer\Oic\EntityInterface;
use InoOicServer\Oic\User\UserInterface;
use InoOicServer\Util\ConvertToDateTimeTrait;


class AuthSession implements EntityInterface
{
    
    use ConvertToDateTimeTrait;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var DateTime
     */
    protected $createTime;

    /**
     * @var DateTime
     */
    protected $expirationTime;

    /**
     * @var UserInterface
     */
    protected $user;


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
    public function getMethod()
    {
        return $this->method;
    }


    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
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
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * @param UserInterface $user
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }
}