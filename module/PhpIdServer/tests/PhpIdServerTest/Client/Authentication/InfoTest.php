<?php

namespace PhpIdServerTest\Client\Authentication;

use PhpIdServer\Client\Authentication\Info;
use PhpIdServer\Client\Authentication;


class InfoTest extends \PHPUnit_Framework_TestCase
{


    public function testConstructor ()
    {
        $method = 'secret';
        $options = array(
            'foo' => 'bar'
        );
        
        $info = new Info($method, $options);
        
        $this->assertSame($method, $info->getMethod());
        $this->assertSame($options, $info->getOptions());
    }

}