<?php

namespace InoOicServerTest\Oic\User;

use InoOicServer\Oic\User\User;


class UserTest extends \PHPUnit_Framework_Testcase
{


    public function testSettersAndGetters()
    {
        $id = 'testuser';
        $firstName = 'Test';
        $familyName = 'User';
        $email = 'test.user@email.com';
        
        $user = new User();
        $user->setId($id);
        $user->setFirstName($firstName);
        $user->setFamilyName($familyName);
        $user->setEmail($email);
        
        $this->assertSame($id, $user->getId());
        $this->assertSame($firstName, $user->getFirstName());
        $this->assertSame($familyName, $user->getFamilyName());
        $this->assertSame($email, $user->getEmail());
    }
}