<?php

namespace InoOicServerTest\Util;

use Zend\Http\Header\Cookie;
use Zend\Http;
use InoOicServer\Util\CookieManager;


class CookieManagerTest extends \PHPUnit_Framework_TestCase
{


    public function testGetCookieValue()
    {
        $name = 'foo';
        $value = 'bar';
        
        $httpRequest = new Http\Request();
        $httpRequest->getHeaders()->addHeader(new Cookie(array(
            $name => $value
        )));
        
        $manager = new CookieManager();
        
        $this->assertSame($value, $manager->getCookieValue($httpRequest, $name));
    }
}