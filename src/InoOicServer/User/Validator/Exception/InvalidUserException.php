<?php

namespace InoOicServer\User\Validator\Exception;


class InvalidUserException extends \RuntimeException
{

    /**
     * @var string
     */
    protected $redirectUri;


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
}