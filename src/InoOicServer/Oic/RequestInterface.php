<?php

namespace InoOicServer\Oic;

use Zend\Http;


interface RequestInterface
{


    /**
     * @return Http\Request
     */
    public function getHttpRequest();


    /**
     * @param Http\Request $httpRequest
     */
    public function setHttpRequest(Http\Request $httpRequest);
}