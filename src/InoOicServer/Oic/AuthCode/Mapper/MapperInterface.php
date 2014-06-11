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
     * @param string $clientId
     * @param string $scope
     * @return AuthCode|null
     */
    public function fetchBySession($sessionId, $clientId, $scope = null);


    /**
     * @param string $code
     */
    public function delete($code);
}