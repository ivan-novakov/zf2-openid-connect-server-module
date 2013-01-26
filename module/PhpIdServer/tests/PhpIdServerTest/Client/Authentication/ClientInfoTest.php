<?php

namespace PhpIdServerTest\Client\Authentication;

use PhpIdServer\Client\Authentication\Info;
use PhpIdServer\Client\Authentication;


class InfoTest extends \PHPUnit_Framework_TestCase
{


    public function testConstructor ()
    {
        $type = 'secret';
        $options = array(
            'foo' => 'bar'
        );
        
        $info = new Info($type, $options);
        
        $this->assertSame($type, $info->getType());
        $this->assertSame($options, $info->getOptions());
    }

}