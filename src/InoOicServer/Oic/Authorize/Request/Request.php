<?php

namespace InoOicServer\Oic\Authorize\Request;


/**
 * Authorize request entity.
 */
class Request
{

    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $redirectUri;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var string
     */
    protected $responseType;

    /**
     * @var string
     */
    protected $nonce;


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
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }


    /**
     * @param string $redirectUri
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
    }


    /**
     * @return stringunknown_type
     */
    public function getState()
    {
        return $this->state;
    }


    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }


    /**
     * @return string
     */
    public function getResponseType()
    {
        return $this->responseType;
    }


    /**
     * @param string $responseType
     */
    public function setResponseType($responseType)
    {
        $this->responseType = $responseType;
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