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
    protected $scope;

    /**
     * Unique ID (set as a cookie) used to associate the user agent with
     * an existing OIC session.
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