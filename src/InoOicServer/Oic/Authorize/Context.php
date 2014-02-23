<?php

namespace InoOicServer\Oic\Authorize;

use InoOicServer\Oic\Authorize;
use InoOicServer\Oic\User;


/**
 * The context object carries persistent information between redirects 
 * during the "authorize" phase.
 */
class Context
{

    /**
     * @var string
     */
    protected $uniqueId;

    /**
     * @var \DateTime
     */
    protected $createTime;

    /**
     * @var Authorize\Request\Request
     */
    protected $authorizeRequest;

    /**
     * @var User\Authentication\Status
     */
    protected $authStatus;


    /**
     * Constructor.
     */
    public function __construct(\DateTime $createTime = null)
    {
        if (null === $createTime) {
            $createTime = new \DateTime();
        }
        $this->setCreateTime($createTime);
    }


    /**
     * @return \DateTime
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }


    /**
     * @param \DateTime $createTime
     */
    protected function setCreateTime(\DateTime $createTime)
    {
        $this->createTime = $createTime;
    }


    /**
     * @return string
     */
    public function getUniqueId()
    {
        return $this->uniqueId;
    }


    /**
     * @param string $uniqueId
     */
    public function setUniqueId($uniqueId)
    {
        $this->uniqueId = $uniqueId;
    }


    /**
     * @return Authorize\Request\Request
     */
    public function getAuthorizeRequest()
    {
        return $this->authorizeRequest;
    }


    /**
     * @param Authorize\Request\Request $authorizeRequest
     */
    public function setAuthorizeRequest(Authorize\Request\Request $authorizeRequest)
    {
        $this->authorizeRequest = $authorizeRequest;
    }


    /**
     * @return User\Authentication\Status
     */
    public function getAuthStatus()
    {
        return $this->authStatus;
    }


    /**
     * @param User\Authentication\Status $authStatus
     */
    public function setAuthStatus(User\Authentication\Status $authStatus)
    {
        $this->authStatus = $authStatus;
    }
}