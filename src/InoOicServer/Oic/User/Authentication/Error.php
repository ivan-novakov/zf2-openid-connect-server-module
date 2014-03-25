<?php

namespace InoOicServer\Oic\User\Authentication;


/**
 * Authentication error.
 */
class Error
{

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var $extraData
     */
    protected $extraData;


    /**
     * Constructor.
     * 
     * @param string $message
     * @param string $description
     * @param array $extraData
     */
    public function __construct($message, $description = null, array $extraData = array())
    {
        $this->setMessage($message);
        $this->setDescription($description);
        $this->setExtraData($extraData);
    }


    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }


    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }


    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }


    /**
     * @return array
     */
    public function getExtraData()
    {
        return $this->extraData;
    }


    /**
     * @param array $extraData
     */
    public function setExtraData($extraData)
    {
        $this->extraData = $extraData;
    }
}