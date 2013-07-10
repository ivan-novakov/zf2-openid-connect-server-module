<?php

namespace InoOicServerTest\User\UserInfo\Mapper;

use InoOicServer\User\UserInfo\Mapper\ToArray;


class ToArrayTest extends \PHPUnit_Framework_TestCase
{


    public function testGetUserInfoData ()
    {
        $mapper = new ToArray();
        
        $userData = array(
            'id' => 'foo'
        );
        $user = $this->getMock('InoOicServer\User\UserInterface');
        $user->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue($userData));
        
        $this->assertSame($userData, $mapper->getUserInfoData($user));
    }
}