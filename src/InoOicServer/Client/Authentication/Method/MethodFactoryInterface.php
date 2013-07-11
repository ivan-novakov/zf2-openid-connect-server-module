<?php

namespace InoOicServer\Client\Authentication\Method;


interface MethodFactoryInterface
{


    /**
     * Creates an authentication method class.
     * 
     * @param array $methodConfig
     * @return MethodInterface
     */
    public function createAuthenticationMethod(array $methodConfig);
}