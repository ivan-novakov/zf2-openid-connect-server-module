<?php

namespace PhpIdServer\Session;

use PhpIdServer\Entity\Entity;
use PhpIdServer\Client\Client;
use PhpIdServer\User\User;
use PhpIdServer\Authentication;


class Session extends Entity
{

    const FIELD_ID = 'id';

    const FIELD_USER_ID = 'user_id';

    const FIELD_CLIENT_ID = 'client_id';

    const FIELD_AUTHN_TIME = 'authn_time';

    const FIELD_AUTHN_METHOD = 'authn_method';

    const FIELD_AUTHORIZATION_CODE = 'authorization_code';

    const FIELD_USER_DATA = 'user_data';

    const FIELD_ACCESS_TOKEN = 'access_token';

    const FIELD_REFRESH_TOKEN = 'refresh_token';

    const FIELD_CTIME = 'ctime';

    const FIELD_MTIME = 'mtime';


    static public function create ($sessionId, $userId, $clientId, $authenticationTime, $authenticationMethod, 
        $authorizationCode, $userData, $accessToken, $refreshToken, $ctime = NULL, $mtime = NULL)
    {
        return new self(array(
            self::FIELD_ID => $sessionId, 
            self::FIELD_USER_ID => $userId, 
            self::FIELD_CLIENT_ID => $clientId, 
            self::FIELD_AUTHN_TIME => $authenticationTime, 
            self::FIELD_AUTHN_METHOD => $authenticationMethod, 
            self::FIELD_AUTHORIZATION_CODE => $authorizationCode, 
            self::FIELD_USER_DATA => $userData, 
            self::FIELD_ACCESS_TOKEN => $accessToken, 
            self::FIELD_REFRESH_TOKEN => $refreshToken, 
            self::FIELD_CTIME => $ctime, 
            self::FIELD_MTIME => $mtime
        ));
    }


    public function getId ()
    {
        return $this->getValue(self::FIELD_ID);
    }


    public function getUserId ()
    {
        return $this->getValue(self::FIELD_USER_ID);
    }


    public function getClientId ()
    {
        return $this->getValue(self::FIELD_CLIENT_ID);
    }


    public function getAuthenticationTime ()
    {
        return $this->getValue(self::FIELD_AUTHN_TIME);
    }


    public function getAuthenticationMethod ()
    {
        return $this->getValue(self::FIELD_AUTHN_METHOD);
    }


    public function getAuthorizationCode ()
    {
        return $this->getValue(self::FIELD_AUTHORIZATION_CODE);
    }


    public function getUserData ()
    {
        return $this->getValue(self::FIELD_USER_DATA);
    }


    public function setAccessToken ($token)
    {
        $this->setValue(self::FIELD_ACCESS_TOKEN);
    }


    public function getAccessToken ()
    {
        return $this->getValue(self::FIELD_ACCESS_TOKEN);
    }


    public function setRefreshToken ($token)
    {
        $this->setValue(self::FIELD_REFRESH_TOKEN);
    }


    public function getRefreshToken ()
    {
        return $this->getValue(self::FIELD_REFRESH_TOKEN);
    }


    public function getCtime ()
    {
        return $this->getValue(self::FIELD_CTIME);
    }


    public function getMtime ()
    {
        return $this->getValue(self::FIELD_MTIME);
    }
}