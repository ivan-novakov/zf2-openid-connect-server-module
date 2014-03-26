<?php

namespace InoOicServer\Util;

use DateTime;


trait ConvertToDateTimeTrait
{


    public function convertToDateTime($value)
    {
        if ($value instanceof DateTime) {
            return $value;
        }
        
        if (! is_string($value)) {
            throw new Exception\InvalidDateTimeValueException(sprintf("Invalid value type '%s' for a datetime value", gettype($value)));
        }
        
        try {
            $dateTime = new DateTime($value);
        } catch (\Exception $e) {
            throw new Exception\InvalidDateTimeValueException(sprintf("Invalid datetime string '%s'", $value), null, $e);
        }
        
        return $dateTime;
    }
}