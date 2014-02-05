<?php

namespace InoOicServer\Oic\User\Authentication;

use DateTime;
use InoOicServer\Oic\User\UserInterface;


/**
 * Authentication status - tracks user's authentication information.
 */
class Status
{

    /**
     * @var boolean
     */
    protected $authenticated;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var DateTime
     */
    protected $time;

    /**
     * @var UserInterface
     */
    protected $identity;

    /**
     * @var Error
     */
    protected $error;


    /**
     * @return boolean
     */
    public function isAuthenticated()
    {
        return $this->authenticated;
    }


    /**
     * @param boolean $authenticated
     */
    public function setAuthenticated($authenticated)
    {
        $this->authenticated = $authenticated;
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
    public function getTime()
    {
        return $this->time;
    }


    /**
     * @param DateTime $time
     */
    public function setTime(DateTime $time)
    {
        $this->time = $time;
    }


    /**
     * @return UserInterface
     */
    public function getIdentity()
    {
        return $this->identity;
    }


    /**
     * @param UserInterface $identity
     */
    public function setIdentity(UserInterface $identity)
    {
        $this->identity = $identity;
    }


    /**
     * @return Error
     */
    public function getError()
    {
        return $this->error;
    }


    /**
     * @param Error $error
     */
    public function setError(Error $error)
    {
        $this->error = $error;
    }
}