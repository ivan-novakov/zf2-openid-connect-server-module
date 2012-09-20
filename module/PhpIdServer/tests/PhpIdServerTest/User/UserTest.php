<?php

namespace PhpIdServerTest\User;

use PhpIdServer\User\User;


class UserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The user entity object.
     * 
     * @var User
     */
    protected $_user = NULL;


    public function setUp ()
    {
        $this->_user = new User(array(
            User::FIELD_ID => 'vomacka@example.cz', 
            User::FIELD_NAME => 'Franta Vomacka', 
            User::FIELD_GIVEN_NAME => 'Franta', 
            User::FIELD_FAMILY_NAME => 'Vomacka', 
            User::FIELD_NICKNAME => 'killer_vom', 
            User::FIELD_EMAIL => 'franta.vomacka@example.cz'
        ));
    }


    public function testGetId ()
    {
        $this->assertEquals('vomacka@example.cz', $this->_user->getId());
    }


    public function testGetName ()
    {
        $this->assertEquals('Franta Vomacka', $this->_user->getName());
    }


    public function testGetGivenName ()
    {
        $this->assertEquals('Franta', $this->_user->getGivenName());
    }


    public function testGetFamilyName ()
    {
        $this->assertEquals('Vomacka', $this->_user->getFamilyName());
    }


    public function testGetNickname ()
    {
        $this->assertEquals('killer_vom', $this->_user->getNickname());
    }


    public function testGetEmail ()
    {
        $this->assertEquals('franta.vomacka@example.cz', $this->_user->getEmail());
    }
}