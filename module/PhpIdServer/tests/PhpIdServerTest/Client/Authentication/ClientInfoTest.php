<?php
namespace PhpIdServerTest\Client\Authentication;
use PhpIdServer\Client\Authentication\ClientInfo;
use PhpIdServer\Client\Authentication;


class ClientInfoTest extends \PHPUnit_Framework_TestCase
{


    public function testConstructor ()
    {
        $info = new ClientInfo(Authentication\Type::SECRET, array(
            'foo' => 'bar'
        ));
        
        $this->assertEquals(Authentication\Type::SECRET, $info->getType());
    }


    public function testUnsupportedTypeException ()
    {
        $this->setExpectedException('\PhpIdServer\Client\Authentication\Exception\UnsupportedAuthenticationTypeException');
        $info = new ClientInfo('_some_unsupported_type_string');
    }
}