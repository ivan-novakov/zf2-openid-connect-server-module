<?php

namespace InoOicServerTest\Oic\Authorize;

use InoOicServer\Oic\Authorize\Redirect;


class RedirectTest extends \PHPUnit_Framework_TestCase
{


    public function testConstructorWithValidType()
    {
        $type = Redirect::TO_AUTHENTICATION;
        $url = 'http://test';
        $redirect = new Redirect($type, $url);
        
        $this->assertSame($type, $redirect->getType());
        $this->assertSame($url, $redirect->getUrl());
    }


    public function testConstructorWithInvalidType()
    {
        $this->setExpectedException('InvalidArgumentException', 'Invalid type');
        
        $redirect = new Redirect('invalid');
    }
}