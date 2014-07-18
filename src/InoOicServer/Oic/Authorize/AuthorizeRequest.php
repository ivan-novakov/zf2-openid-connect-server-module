<?php

namespace InoOicServer\Oic\Authorize;

use InoOicServer\Oic\AbstractRequest;


/**
 * Authorize request entity.
 */
class AuthorizeRequest extends AbstractRequest
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
    protected $scope;

    /**
     * @var string
     */
    protected $nonce;

    /**
     * Unique ID (set as a cookie) used to associate the user with an
     * existing OIC session.
     *
     * @var string
     */
    protected $sessionId;

    /**
     * Unique ID (set as a cookie) used to associate the user agent with
     * an authentication session.
     *
     * @var string
     */
    protected $authenticationSessionId;


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
     * @return string
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
    public function getScope()
    {
        return $this->scope;
    }


    /**
     * @param string $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
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


    /**
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }


    /**
     * @param string $sessionId
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
    }


    /**
     * @return string
     */
    public function getAuthenticationSessionId()
    {
        return $this->authenticationSessionId;
    }


    /**
     * @param string $id
     */
    public function setAuthenticationSessionId($id)
    {
        $this->authenticationSessionId = $id;
    }
}