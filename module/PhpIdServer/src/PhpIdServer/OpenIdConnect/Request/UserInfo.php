<?php

namespace PhpIdServer\OpenIdConnect\Request;


/**
 * User info request.
 *
 */
class UserInfo extends AbstractRequest
{

    const FIELD_SCHEMA = 'schema';


    public function getSchema ()
    {
        return $this->_getGetParam(self::FIELD_SCHEMA);
    }


    public function getAuthorization ()
    {}


    protected function _validate ()
    {
        return array();
    }
}