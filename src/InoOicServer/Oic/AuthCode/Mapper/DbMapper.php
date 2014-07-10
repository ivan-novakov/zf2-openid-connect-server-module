<?php

namespace InoOicServer\Oic\AuthCode\Mapper;

use InoOicServer\Oic\AuthCode\AuthCode;


class DbMapper implements MapperInterface
{


    public function save(AuthCode $authCode)
    {}


    public function fetch($code)
    {}


    public function fetchBySession($sessionId, $clientId, $scope = null)
    {}


    public function delete($code)
    {}
}