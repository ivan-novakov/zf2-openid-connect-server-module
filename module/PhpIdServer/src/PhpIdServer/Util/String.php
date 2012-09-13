<?php

namespace PhpIdServer\Util;

use Zend\Filter\Word\CamelCaseToUnderscore;
use Zend\Filter\Word\UnderscoreToCamelCase;


class String
{


    static public function underscoreToCamelCase ($value)
    {
        $filter = new UnderscoreToCamelCase();
        return $filter->filter($value);
    }


    static public function camelCaseToUnderscore ($value)
    {
        $filter = new CamelCaseToUnderscore();
        return strtolower($filter->filter($value));
    }


    static public function dbDateTimeFormat ($dateTime = NULL)
    {
        if (NULL === $dateTime) {
            $dateTime = new \DateTime('now');
        } elseif (! ($dateTime instanceof \DateTime)) {
            $dateTime = new \DateTime($dateTime);
        }
        
        return $dateTime->format('Y-m-d H:i:s');
    }
}