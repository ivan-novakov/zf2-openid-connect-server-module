<?php
namespace InoOicServer\Oic\Authorize\Context;

use DateTime;
use InoOicServer\Oic\User;
use InoOicServer\Oic\Authorize\AuthorizeRequest;

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
     * @var DateTime
     */
    protected $createTime;

    /**
     * @var AuthorizeRequest
     */
    protected $authorizeRequest;

    /**
     * @var User\Authentication\Status
     */
    protected $authStatus;

    /**
     * Constructor.
     *
     * @param DateTime $createTime
     */
    public function __construct(DateTime $createTime = null)
    {
        if (null === $createTime) {
            $createTime = new DateTime();
        }
        $this->setCreateTime($createTime);
    }

    /**
     * @return DateTime
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * @param DateTime $createTime
     */
    protected function setCreateTime(DateTime $createTime)
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
     * @return AuthorizeRequest
     */
    public function getAuthorizeRequest()
    {
        return $this->authorizeRequest;
    }

    /**
     * @param AuthorizeRequest $authorizeRequest
     */
    public function setAuthorizeRequest(AuthorizeRequest $authorizeRequest)
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