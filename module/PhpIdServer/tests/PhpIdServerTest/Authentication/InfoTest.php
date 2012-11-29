<?php

namespace PhpIdServerTest\Authentication;

use PhpIdServer\Authentication\Info;


class InfoTest extends \PHPUnit_Framework_TestCase
{


    public function testFactorySuccess ()
    {
        $method = 'dummy';
        $time = new \DateTime('2012-09-10 00:00:00');
        
        $info = Info::factorySuccess($method, $time);
        
        $this->assertInstanceOf('PhpIdServer\Authentication\Info', $info);
        $this->assertTrue($info->isAuthenticated());
        $this->assertSame($method, $info->getMethod());
        $this->assertSame($time, $info->getTime());
    }


    public function testFactoryFailure ()
    {
        $method = 'dummy';
        $time = new \DateTime('2012-09-10 00:00:00');
        $error = 'error';
        $description = 'description';
        
        $info = Info::factoryFailure($method, $error, $description, $time);
        
        $this->assertInstanceOf('PhpIdServer\Authentication\Info', $info);
        $this->assertFalse($info->isAuthenticated());
        $this->assertSame($method, $info->getMethod());
        $this->assertSame($time, $info->getTime());
        $this->assertSame($error, $info->getError());
        $this->assertSame($description, $info->getErrorDescription());
    }


    /**
     * @dataProvider isExpiredDataProvider
     */
    public function testIsExpired ($timeString, $expireTimeout, $addSeconds, $isExpired)
    {
        $time = new \DateTime($timeString);
        $info = Info::factorySuccess('dummy', $time);
        
        $expireCheckTime = $time->getTimestamp() + $addSeconds;
        $this->assertSame($isExpired, $info->isExpired($expireTimeout, $expireCheckTime));
    }


    public function isExpiredDataProvider ()
    {
        return array(
            array(
                '2012-09-10 00:00:00', 
                10, 
                9, 
                false
            ), 
            array(
                '2012-09-10 00:00:00', 
                10, 
                10, 
                false
            ), 
            array(
                '2012-09-10 00:00:00', 
                10, 
                11, 
                true
            )
        );
    }
}