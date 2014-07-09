<?php

namespace InoOicServer\Oic\Authorize;

use InoOicServer\Oic\Error;
use InoOicServer\Oic\Authorize\Response\ResponseInterface;


class Result
{

    const TYPE_RESPONSE = 'response';

    const TYPE_REDIRECT = 'redirect';

    /**
     * @var string
     */
    protected $type;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var Redirect
     */
    protected $redirect;


    /**
     * Constructs a response result.
     * 
     * @param ResponseInterface $response
     * @return Result
     */
    static public function constructResponseResult(ResponseInterface $response)
    {
        return new self(self::TYPE_RESPONSE, $response);
    }


    /**
     * Constructs a redirect result.
     * 
     * @param Redirect $redirect
     * @return Result
     */
    static public function constructRedirectResult(Redirect $redirect)
    {
        return new self(self::TYPE_REDIRECT, null, $redirect);
    }


    /**
     * Protected constructor.
     * 
     * @param string $type
     * @param ResponseInterface $response
     * @param Error $error
     * @param Redirect $redirect
     */
    protected function __construct($type, ResponseInterface $response = null, Redirect $redirect = null)
    {
        $this->type = $type;
        
        if (null !== $response) {
            $this->response = $response;
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
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }


    /**
     * @return Redirect
     */
    public function getRedirect()
    {
        return $this->redirect;
    }
}