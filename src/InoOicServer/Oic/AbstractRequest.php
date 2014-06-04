<?php

namespace InoOicServer\Oic;

use Zend\Http;


class AbstractRequest implements RequestInterface
{

    /**
     * @var Http\Request
     */
    protected $httpRequest;


    /**
     * @return Http\Request
     */
    public function getHttpRequest()
    {
        return $this->httpRequest;
    }


    /**
     * @param Http\Request $httpRequest
     */
    public function setHttpRequest(Http\Request $httpRequest)
    {
        $this->httpRequest = $httpRequest;
    }
}