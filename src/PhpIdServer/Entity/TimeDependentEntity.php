<?php

namespace PhpIdServer\Entity;

use PhpIdServer\Util\String;


abstract class TimeDependentEntity extends Entity
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
        if ($timeString instanceof \DateTime) {
            return $timeString;
        }
        
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
        if (NULL !== $format) {
            return $dateTime->format($format);
        }
        
        return String::dbDateTimeFormat($dateTime);
    }


    /**
     * Converts selected array values from DateTime object to string.
     *
     * @param array $arrayData
     * @param array $fields
     * @return array
     */
    protected function _arrayDateObjectToTimeString (Array $arrayData, Array $fields)
    {
        foreach ($fields as $fieldName) {
            $arrayData[$fieldName] = $this->_dateObjectToTimeString($this->getValue($fieldName));
        }
        
        return $arrayData;
    }
}