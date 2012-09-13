<?php

namespace PhpIdServer\Session;

use PhpIdServer\Session\Token\AbstractToken;
use PhpIdServer\Entity\Entity;
use PhpIdServer\Client\Client;
use PhpIdServer\User\User;
use PhpIdServer\Authentication;


/**
 * Session entity.
 * 
 * @method string getId()
 * @method string getUserId()
 * @method DateTime getCreateTime()
 * @method DateTime getModifyTime()
 * @method DateTime getExpirationTime()
 * @method DateTime getAuthenticationTime()
 * @method string getAuthenticationMethod()
 * @method string getUserData()
 *
 */
class Session extends AbstractToken
{

    const FIELD_ID = 'id';

    const FIELD_USER_ID = 'user_id';

    const FIELD_CREATE_TIME = 'create_time';

    const FIELD_MODIFY_TIME = 'modify_time';

    const FIELD_EXPIRATION_TIME = 'expiration_time';

    const FIELD_AUTHENTICATION_TIME = 'authentication_time';

    const FIELD_AUTHENTICATION_METHOD = 'authentication_method';

    const FIELD_USER_DATA = 'user_data';

    protected $_fields = array(
        self::FIELD_ID, 
        self::FIELD_USER_ID, 
        self::FIELD_CREATE_TIME, 
        self::FIELD_MODIFY_TIME, 
        self::FIELD_EXPIRATION_TIME, 
        self::FIELD_AUTHENTICATION_TIME, 
        self::FIELD_AUTHENTICATION_METHOD, 
        self::FIELD_USER_DATA
    );


    public function setCreateTime ($timeString)
    {
        $this->setValue(self::FIELD_CREATE_TIME, $this->_timeStringToDateObject($timeString));
    }


    public function setModifyTime ($timeString)
    {
        $this->setValue(self::FIELD_MODIFY_TIME, $this->_timeStringToDateObject($timeString));
    }


    public function setExpirationTime ($timeString)
    {
        $this->setValue(self::FIELD_EXPIRATION_TIME, $this->_timeStringToDateObject($timeString));
    }


    public function setAuthenticationTime ($timeString)
    {
        $this->setValue(self::FIELD_AUTHENTICATION_TIME, $this->_timeStringToDateObject($timeString));
    }


    public function toArray ()
    {
        $arrayData = parent::toArray();
        
        return $this->_arrayDateObjectToTimeString($arrayData, array(
            self::FIELD_CREATE_TIME, 
            self::FIELD_MODIFY_TIME, 
            self::FIELD_EXPIRATION_TIME, 
            self::FIELD_AUTHENTICATION_TIME
        ));
    }
}