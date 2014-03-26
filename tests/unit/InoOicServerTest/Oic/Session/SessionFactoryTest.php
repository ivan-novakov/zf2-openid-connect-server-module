<?php

namespace InoOicServerTest\Oic\Session;

use InoOicServer\Oic\Session\SessionFactory;


class SessionFactoryTest extends \PHPUnit_Framework_TestCase
{

    protected $factory;


    public function setUp()
    {
        $this->factory = new SessionFactory();
    }


    public function testCreateSession()
    {
        $this->assertInstanceOf('InoOicServer\Oic\Session\Session', $this->factory->createSession());
    }
}