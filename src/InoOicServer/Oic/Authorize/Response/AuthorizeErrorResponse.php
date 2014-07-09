<?php

namespace InoOicServer\Oic\Authorize\Response;

use InoOicServer\Oic\Error;


class AuthorizeErrorResponse implements ResponseInterface
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
     * @var Error
     */
    protected $error;


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
     * @return Error
     */
    public function getError()
    {
        return $this->error;
    }


    /**
     * @param Error $error
     */
    public function setError(Error $error)
    {
        $this->error = $error;
    }
}