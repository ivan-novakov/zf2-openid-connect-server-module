<?php

namespace PhpIdServerTest\Client\Authentication;

use PhpIdServer\Client\Authentication\Info;
use PhpIdServer\Client\Authentication;


class InfoTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Info
     */
    protected $info = null;


    public function setUp()
    {
        $method = 'secret';
        $options = array(
            'foo' => 'bar'
        );
        
        $this->info = new Info($method, $options);
    }


    public function testConstructor()
    {
        $this->assertSame('secret', $this->info->getMethod());
        $this->assertSame(array(
            'foo' => 'bar'
        ), $this->info->getOptions());
    }


    public function testGetOption()
    {
        $this->assertSame('bar', $this->info->getOption('foo'));
    }


    public function testGetOptionNonExistent()
    {
        $this->assertNull($this->info->getOption('nonexistent'));
    }
}