<?php

namespace PhpIdServerTest\Client\Authentication;

use PhpIdServer\Client\Authentication\ClientInfo;
use PhpIdServer\Client\Authentication;


class ClientInfoTest extends \PHPUnit_Framework_TestCase
{


    public function testConstructor ()
    {
        $options = array(
            'foo' => 'bar'
        );
        
        $info = new ClientInfo(Authentication\Type::SECRET, $options);
        
        $this->assertSame(Authentication\Type::SECRET, $info->getType());
        $this->assertSame($options, $info->getOptions());
    }


    public function testUnsupportedTypeException ()
    {
        $this->setExpectedException('\PhpIdServer\Client\Authentication\Exception\UnsupportedAuthenticationTypeException');
        $info = new ClientInfo('_some_unsupported_type_string');
    }
}