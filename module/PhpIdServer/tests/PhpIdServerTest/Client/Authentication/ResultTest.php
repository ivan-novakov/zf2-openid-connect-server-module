<?php

namespace PhpIdServerTest\Client\Authentication;

use PhpIdServer\Client\Authentication\Result;


class ResultTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Result
     */
    protected $result = null;


    public function setUp ()
    {
        $this->result = new Result();
    }


    public function testConstructorWithNoArgs ()
    {
        $result = new Result();
        $this->assertFalse($result->isAuthenticated());
    }


    public function testSetResult ()
    {
        $authenticated = false;
        $reason = 'error';
        
        $this->result->setResult($authenticated, $reason);
        $this->assertSame($authenticated, $this->result->isAuthenticated());
        $this->assertSame($reason, $this->result->getNotAuthenticatedReason());
    }


    public function testSetAuthenticated ()
    {
        $this->result->setAuthenticated();
        $this->assertTrue($this->result->isAuthenticated());
    }


    public function testSetNotAuthenticated ()
    {
        $reason = 'error auth';
        $this->result->setNotAuthenticated($reason);
        $this->assertFalse($this->result->isAuthenticated());
        $this->assertSame($reason, $this->result->getNotAuthenticatedReason());
    }
}