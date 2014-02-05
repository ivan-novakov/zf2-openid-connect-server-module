<?php

namespace InoOicServerTest\Oic\User\Factory;

use InoOicServer\Oic\User\Factory\Factory;


class FactoryTest extends \PHPUnit_Framework_Testcase
{


    public function testCreateUser()
    {
        $data = array(
            'id' => 'testuser',
            'first_name' => 'Test',
            'family_name' => 'User',
            'email' => 'test.user@email.com'
        );
        
        $factory = new Factory();
        $user = $factory->createUser($data);
        
        $this->assertInstanceOf('InoOicServer\Oic\User\User', $user);
        $this->assertSame($data['id'], $user->getId());
        $this->assertSame($data['first_name'], $user->getFirstName());
        $this->assertSame($data['family_name'], $user->getFamilyName());
        $this->assertSame($data['email'], $user->getEmail());
    }
}