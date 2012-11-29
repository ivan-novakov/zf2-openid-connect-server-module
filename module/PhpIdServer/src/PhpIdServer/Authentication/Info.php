<?php

namespace PhpIdServer\Authentication;

use PhpIdServer\Entity\TimeDependentEntity;


/**
 * Entity for storing authentication info.
 * 
 * @method string getMethod()
 * @method \DateTime getTime()
 * @method string getError()
 * @method string getErrorDescription()
 *
 */
class Info extends TimeDependentEntity
{

    const FIELD_AUTHENTICATED = 'authenticated';

    const FIELD_ERROR = 'error';

    const FIELD_ERROR_DESCRIPTION = 'error_description';

    const FIELD_METHOD = 'method';

    const FIELD_TIME = 'time';

    protected $_fields = array(
        self::FIELD_AUTHENTICATED, 
        self::FIELD_ERROR, 
        self::FIELD_ERROR_DESCRIPTION, 
        self::FIELD_METHOD, 
        self::FIELD_TIME
    );


    static public function factorySuccess ($method, $time = null)
    {
        if (null === $time) {
            $time = new \DateTime('now');
        }
        
        return new self(array(
            self::FIELD_AUTHENTICATED => true, 
            self::FIELD_METHOD => $method, 
            self::FIELD_TIME => $time
        ));
    }


    static public function factoryFailure ($method, $error, $description = '', $time = null)
    {
        if (null === $time) {
            $time = new \DateTime('now');
        }
        
        return new self(array(
            self::FIELD_AUTHENTICATED => false, 
            self::FIELD_ERROR => $error, 
            self::FIELD_ERROR_DESCRIPTION => $description, 
            self::FIELD_METHOD => $method, 
            self::FIELD_TIME => $time
        ));
    }


    public function isAuthenticated ()
    {
        return (boolean) $this->getValue(self::FIELD_AUTHENTICATED);
    }


    public function setTime ($value)
    {
        $this->setValue(self::FIELD_TIME, $this->_timeStringToDateObject($value));
    }


    /**
     * Returns true, if the amount of $expireTimout seconds has been passed from the authentication instant.
     * 
     * @param integer $expireTimeout
     * @param integer $expireCheckInstant
     * @return boolean
     */
    public function isExpired ($expireTimeout, $expireCheckInstant = null)
    {
        if (! $expireCheckInstant) {
            $expireCheckInstant = time();
        }
        
        $expireTimeout = intval($expireTimeout);
        if ($expireTimeout <= 0) {
            return true;
        }
        
        $authenticationInstant = $this->getTime()
            ->getTimestamp();
        if ($expireCheckInstant - $authenticationInstant > $expireTimeout) {
            return true;
        }
        
        return false;
    }


    public function toArray ()
    {
        $arrayData = parent::toArray();
        
        return $this->_arrayDateObjectToTimeString($arrayData, array(
            self::FIELD_TIME
        ));
    }


    public function __toString ()
    {
        return sprintf("[method=%s, time=%s]", $this->getMethod(), $this->getTime()
            ->format('c'));
    }
}