<?php

namespace InoOicServer\OpenIdConnect\Response;


interface ResponseInterface
{


    /**
     * @return \Zend\Http\Response
     */
    public function getHttpResponse ();
}