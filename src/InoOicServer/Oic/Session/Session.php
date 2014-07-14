<?php

namespace InoOicServer\Oic\Session;

use DateTime;
use InoOicServer\Oic\EntityInterface;
use InoOicServer\Util\ConvertToDateTimeTrait;
use InoOicServer\Oic\User\UserInterface;


/**
 * The OIC session entity.
 * 
 * Holds user data for a period of time:
 * - user authentication
 * - user data
 * 
 */
class Session implements EntityInterface
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
    protected $authSessionId;

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
    protected $nonce;


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
    public function getAuthSessionId()
    {
        return $this->authSessionId;
    }


    /**
     * @param string $authSessionId
     */
    public function setAuthSessionId($authSessionId)
    {
        $this->authSessionId = $authSessionId;
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
    public function getNonce()
    {
        return $this->nonce;
    }


    /**
     * @param string $nonce
     */
    public function setNonce($nonce)
    {
        $this->nonce = $nonce;
    }
}