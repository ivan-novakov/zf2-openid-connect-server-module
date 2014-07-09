<?php

namespace InoOicServer\Oic\Authorize\Response;


class AuthorizeResponse implements ResponseInterface
{

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
    protected $code;

    /**
     * @var string
     */
    protected $sessionId;

    /**
     * @var string
     */
    protected $authSessionId;


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
    public function getCode()
    {
        return $this->code;
    }


    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
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
}