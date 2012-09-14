<?php

namespace PhpIdServerTest\Authentication;

use PhpIdServer\Authentication\Info;


class InfoTest extends \PHPUnit_Framework_TestCase
{


    public function testPopulate ()
    {
        $data = array(
            Info::FIELD_METHOD => 'dummy', 
            INFO::FIELD_TIME => '2012-09-10 00:00:00'
        );
        
        $info = new Info($data);
        
        $this->assertEquals($data, $info->toArray());
    }
}