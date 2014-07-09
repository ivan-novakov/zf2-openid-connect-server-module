<?php

namespace InoOicServer\Oic\Authorize;

use InoOicServer\Oic\Error;


class Result
{

    const TYPE_RESPONSE = 'response';

    const TYPE_ERROR = 'error';

    const TYPE_REDIRECT = 'redirect';

    /**
     * @var string
     */
    protected $type;

    /**
     * @var AuthorizeResponse
     */
    protected $response;

    /**
     * @var Error
     */
    protected $error;

    /**
     * @var Redirect
     */
    protected $redirect;


    static public function constructResponseResult(AuthorizeResponse $response)
    {
        return new self(self::TYPE_RESPONSE, $response);
    }


    static public function constructErrorResult(Error $error)
    {
        return new self(self::TYPE_ERROR, null, $error);
    }


    static public function constructRedirectResult(Redirect $redirect)
    {
        return new self(self::TYPE_REDIRECT, null, null, $redirect);
    }


    protected function __construct($type, AuthorizeResponse $response = null, Error $error = null, Redirect $redirect = null)
    {
        $this->type = $type;
        
        if (null !== $response) {
            $this->response = $response;
        }
        
        if (null !== $error) {
            $this->error = $error;
        }
        
        if (null !== $redirect) {
            $this->redirect = $redirect;
        }
    }


    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * @return AuthorizeResponse
     */
    public function getResponse()
    {
        return $this->response;
    }


    /**
     * @return Error
     */
    public function getError()
    {
        return $this->error;
    }


    /**
     * @return Redirect
     */
    public function getRedirect()
    {
        return $this->redirect;
    }
}