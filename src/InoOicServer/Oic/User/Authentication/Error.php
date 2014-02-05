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


    public function __construct($message, $description = null)
    {
        $this->setMessage($message);
        $this->setDescription($description);
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
}