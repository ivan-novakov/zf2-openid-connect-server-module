<?php

namespace PhpIdServer\Session\Token;

use PhpIdServer\Entity\Entity;


/**
 * Abstract entity, subclass for entities dealing with tokens/codes and issue/expiration times.
 *
 */
class AbstractToken extends Entity
{


    /**
     * Converts a string into a DateTime object.
     * 
     * @param string $timeString
     * @throws Exception\InvalidTimeFormatException
     * @return \DateTime
     */
    protected function _timeStringToDateObject ($timeString)
    {
        try {
            return new \DateTime($timeString);
        } catch (\Exception $e) {
            throw new Exception\InvalidTimeFormatException($timeString, $e->getMessage());
        }
    }


    /**
     * Converts a DateTime object into a string.
     * 
     * @param \DateTime $dateTime
     * @return string
     */
    protected function _dateObjectToTimeString (\DateTime $dateTime, $format = NULL)
    {
        if (NULL === $format) {
            $format = 'Y-m-d H:i:s';
        }
        return $dateTime->format($format);
    }


    protected function _arrayDateObjectToTimeString (Array $arrayData, Array $fields)
    {
        foreach ($fields as $fieldName) {
            $arrayData[$fieldName] = $this->_dateObjectToTimeString($this->getValue($fieldName));
        }
        
        return $arrayData;
    }
}