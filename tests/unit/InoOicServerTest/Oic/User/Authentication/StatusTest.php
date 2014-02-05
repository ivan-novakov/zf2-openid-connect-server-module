<?php

namespace InoOicServerTest\Oic\User\Authentication;

use InoOicServer\Oic\User\Authentication\Status;


class StatusTest extends \PHPUnit_Framework_Testcase
{


    public function testSettersAndGetters()
    {
        $authenticated = true;
        $method = 'dummy';
        $time = new \DateTime();
        $identity = $this->getMock('InoOicServer\Oic\User\UserInterface');
        $error = $this->getMockBuilder('InoOicServer\Oic\User\Authentication\Error')
            ->disableOriginalConstructor()
            ->getMock();
        
        $status = new Status();
        $status->setAuthenticated($authenticated);
        $status->setMethod($method);
        $status->setTime($time);
        $status->setIdentity($identity);
        $status->setError($error);
        
        $this->assertTrue($status->isAuthenticated());
        $this->assertSame($method, $status->getMethod());
        $this->assertSame($time, $status->getTime());
        $this->assertSame($identity, $status->getIdentity());
        $this->assertSame($error, $status->getError());
    }
}