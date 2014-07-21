<?php
namespace InoOicServer\Oic\AuthSession\Mapper;

use InoOicServer\Oic\AuthSession\AuthSession;

interface MapperInterface
{

    /**
     * @param AuthSession $authSession
     */
    public function save(AuthSession $authSession);

    /**
     * @param string $id
     * @return AuthSession|null
     */
    public function fetch($id);

    /**
     * Fetches an auth session by user and method. There should be only one session per user/method.
     * 
     * @param string $userId
     * @param string $methodName
     */
    public function fetchByUserAndMethod($userId, $methodName);

    /**
     * @param string $id
     */
    public function delete($id);
}