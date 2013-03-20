<?php

namespace PhpIdServerTest\Util;

use PhpIdServer\Util\String;


class StringTest extends \PHPUnit_Framework_TestCase
{


    public function testUnderscoreToCamelCase ()
    {
        $this->assertEquals('UnderscoreToCamelCase', String::underscoreToCamelCase('underscore_to_camel_case'));
    }


    public function testCamelCaseToUnderscore ()
    {
        $this->assertEquals('camel_case_to_underscore', String::camelCaseToUnderscore('CamelCaseToUnderscore'));
    }


    public function testDbDateTimeFormatWithTime ()
    {
        $time = time();
        $expected = date('Y-m-d H:i:s', $time);
        
        $this->assertEquals($expected, String::dbDateTimeFormat(new \DateTime("@$time")));
    }
}