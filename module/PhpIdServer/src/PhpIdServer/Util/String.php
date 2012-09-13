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
}