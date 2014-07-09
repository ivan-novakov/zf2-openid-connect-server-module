<?php

namespace InoOicServerTest\Oic\Authorize;

use InoOicServer\Oic\Authorize\Result;


class ResultTest extends \PHPUnit_Framework_TestCase
{


    public function testConstructResponseResult()
    {
        $response = $this->getMock('InoOicServer\Oic\Authorize\AuthorizeResponse');
        $result = Result::constructResponseResult($response);
        
        $this->assertInstanceOf('InoOicServer\Oic\Authorize\Result', $result);
        $this->assertSame(Result::TYPE_RESPONSE, $result->getType());
        $this->assertSame($response, $result->getResponse());
    }


    public function testConstructErrorResult()
    {
        $error = $this->getMock('InoOicServer\Oic\Error');
        $result = Result::constructErrorResult($error);
        
        $this->assertInstanceOf('InoOicServer\Oic\Authorize\Result', $result);
        $this->assertSame(Result::TYPE_ERROR, $result->getType());
        $this->assertSame($error, $result->getError());
    }


    public function testConstructRedirectResult()
    {
        $redirect = $this->getMockBuilder('InoOicServer\Oic\Authorize\Redirect')
            ->disableOriginalConstructor()
            ->getMock();
        $result = Result::constructRedirectResult($redirect);
        
        $this->assertInstanceOf('InoOicServer\Oic\Authorize\Result', $result);
        $this->assertSame(Result::TYPE_REDIRECT, $result->getType());
        $this->assertSame($redirect, $result->getRedirect());
    }
}