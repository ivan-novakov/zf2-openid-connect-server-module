<?php

namespace InoOicServer\OpenIdConnect\Request;


interface RequestInterface
{


    /**
     * Returns the underlying HTTP request object.
     *
     * @return \Zend\Http\Request
     */
    public function getHttpRequest();
}

