<?php

namespace PhpIdServer\Authentication;

use PhpIdServer\Entity\TimeDependentEntity;


/**
 * Entity for storing authentication info.
 * 
 * @method string getMethod()
 * @method \DateTime getTime()
 *
 */
class Info extends TimeDependentEntity
{

    const FIELD_METHOD = 'method';

    const FIELD_TIME = 'time';

    protected $_fields = array(
        self::FIELD_METHOD, 
        self::FIELD_TIME
    );


    public function setTime ($value)
    {
        $this->setValue(self::FIELD_TIME, $this->_timeStringToDateObject($value));
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