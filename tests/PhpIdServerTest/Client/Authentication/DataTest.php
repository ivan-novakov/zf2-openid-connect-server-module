<?php

namespace PhpIdServerTest\Client\Authentication;

use PhpIdServer\Client\Authentication\Data;


class DataTest extends \PHPUnit_Framework_TestCase
{


    public function testConstructor()
    {
        $method = 'dummy';
        $params = array(
            'auth' => 'secret'
        );
        $data = new Data($method, $params);
        
        $this->assertSame($method, $data->getMethod());
        $this->assertSame($params, $data->getParams());
    }


    public function testGetParamNonExistent()
    {
        $data = new Data('dummy', array());
        $this->assertNull($data->getParam('foo'));
    }


    public function testGetParam()
    {
        $method = 'dummy';
        $params = array(
            'auth' => 'secret'
        );
        $data = new Data($method, $params);
        
        $this->assertSame('secret', $data->getParam('auth'));
    }
}