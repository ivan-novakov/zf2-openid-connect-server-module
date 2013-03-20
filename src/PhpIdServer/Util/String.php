<?php

namespace PhpIdServer\Util;

use Zend\Filter\Word\CamelCaseToUnderscore;
use Zend\Filter\Word\UnderscoreToCamelCase;


class String
{


    /**
     * Converts an underscore delimited string to a camel case string.
     * 
     * @param string $value
     * @param boolean $capitalize
     * @return string
     */
    static public function underscoreToCamelCase ($value, $capitalize = true)
    {
        $filter = new UnderscoreToCamelCase();
        $value = $filter->filter($value);
        if (! $capitalize) {
            $value = lcfirst($value);
        }
        
        return $value;
    }


    /**
     * Converts a camel case string into an underscore delimited string.
     * 
     * @param string $value
     * @param boolean $lowerCase
     * @return string
     */
    static public function camelCaseToUnderscore ($value, $lowerCase = true)
    {
        $filter = new CamelCaseToUnderscore();
        $value = $filter->filter($value);
        if ($lowerCase) {
            $value = strtolower($value);
        }
        
        return $value;
    }


    /**
     * Returns a MySQL DATETIME formatted string.
     * 
     * @param \DateTime $dateTime
     * @return string
     */
    static public function dbDateTimeFormat (\DateTime $dateTime = NULL)
    {
        if (NULL === $dateTime) {
            $dateTime = new \DateTime('now');
        }
        
        return $dateTime->format('Y-m-d H:i:s');
    }
}