<?php

namespace InoOicServer\Oic\Client;


/**
 * Client (application) entity.
 */
class Client
{

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var array
     */
    protected $redirectUris = array();

    /**
     * @var string
     */
    protected $authenticationMethod;

    /**
     * @var string
     */
    protected $userAuthenticationMethod;


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
    public function getSecret()
    {
        return $this->secret;
    }


    /**
     * @param string $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }


    /**
     * @return array
     */
    public function getRedirectUris()
    {
        return $this->redirectUris;
    }


    /**
     * @param array $redirectUri
     */
    public function setRedirectUris(array $redirectUris)
    {
        $this->redirectUris = $redirectUris;
    }


    /**
     * @param string $redirectUri
     * @return boolean
     */
    public function hasRedirectUri($redirectUri)
    {
        return (in_array($redirectUri, $this->getRedirectUris()));
    }


    /**
     * @return string
     */
    public function getAuthenticationMethod()
    {
        return $this->authenticationMethod;
    }


    /**
     * @param string $authenticationMethod
     */
    public function setAuthenticationMethod($authenticationMethod)
    {
        $this->authenticationMethod = $authenticationMethod;
    }


    /**
     * @return string
     */
    public function getUserAuthenticationMethod()
    {
        return $this->userAuthenticationMethod;
    }


    /**
     * @param string $userAuthenticationMethod
     */
    public function setUserAuthenticationMethod($userAuthenticationMethod)
    {
        $this->userAuthenticationMethod = $userAuthenticationMethod;
    }
}