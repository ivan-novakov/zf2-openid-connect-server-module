<?php

namespace InoOicServer\Oic\AuthCode\Mapper;

use InoOicServer\Oic\AuthCode\AuthCode;


interface MapperInterface
{


    /**
     * @param AuthCode $authCode
     */
    public function save(AuthCode $authCode);


    /**
     * @param string $code
     * @return AuthCode|null
     */
    public function fetch($code);


    /**
     * @param string $sessionId
     * @return AuthCode|null
     */
    public function fetchBySessionId($sessionId);
}