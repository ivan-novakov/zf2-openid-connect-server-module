<?php

namespace InoOicServerTest\Oic\Authorize\Context;

use InoOicServer\Oic\Authorize\Context\ContextFactory;


class ContextFactoryTest extends \PHPUnit_Framework_Testcase
{


    public function testCreateContext()
    {
        $factory = new ContextFactory();
        $context = $factory->createContext();
        
        $this->assertInstanceOf('InoOicServer\Oic\Authorize\Context', $context);
    }
}