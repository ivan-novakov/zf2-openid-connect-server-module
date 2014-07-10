<?php

namespace InoOicServer\Oic\Session\Mapper;

use InoOicServer\Oic\Session\Session;


class DbMapper implements MapperInterface
{


    public function save(Session $session)
    {}


    public function fetch($id)
    {}


    public function fetchByCode($authCode)
    {}


    public function fetchByAccessToken($accessToken)
    {}


    public function fetchByUserId($userId)
    {}


    public function fetchByAuthSessionId($authSessionId)
    {}
}