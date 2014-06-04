<?php

namespace InoOicServer\Oic\Client\Authentication;


class Credentials
{

    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $clientSecret;

    /**
     * @var string
     */
    protected $type;


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
    public function getClientSecret()
    {
        return $this->clientSecret;
    }


    /**
     * @param string $clientSecret
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
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
}