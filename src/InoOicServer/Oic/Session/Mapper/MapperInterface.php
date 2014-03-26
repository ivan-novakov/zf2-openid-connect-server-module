<?php

namespace InoOicServer\Oic\Session\Mapper;

use InoOicServer\Oic\Session\Session;


/**
 * Session persistence interface.
 */
interface MapperInterface
{


    /**
     * Saves the session.
     * 
     * @param Session $session
     */
    public function save(Session $session);


    /**
     * Retrieves the session by the associated authorization code.
     * 
     * @param string $authCode
     * @return Session|null
     */
    public function fetchByCode($authCode);


    /**
     * Retrieves the session by the associated access token.
     * 
     * @param string $accessToken
     * @return Session|null
     */
    public function fetchByAccessToken($accessToken);


    /**
     * Retrieves the session by the corresponding user ID.
     * 
     * @param string $userId
     * @return Session|null
     */
    public function fetchByUserId($userId);


    /**
     * Retrieves the session by the associated authentication session ID.
     * 
     * @param string $authSessionId
     * @return Session|null
     */
    public function fetchByAuthSessionId($authSessionId);
}