<?php

namespace InoOicServerTest\Oic\Authorize\Http;

use InoOicServer\Oic\Authorize\Result;
use InoOicServer\Oic\Authorize\Http\HttpService;


class HttpServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var HttpService
     */
    protected $service;


    public function setUp()
    {
        $this->service = new HttpService();
    }


    public function testCreateAuthorizeRequest()
    {
        $httpRequest = $this->getMock('Zend\Http\Request');
        $authorizeRequest = $this->getMock('InoOicServer\Oic\Authorize\AuthorizeRequest');
        
        $factory = $this->getMock('InoOicServer\Oic\Authorize\AuthorizeRequestFactoryInterface');
        $factory->expects($this->once())
            ->method('createRequest')
            ->with($httpRequest)
            ->will($this->returnValue($authorizeRequest));
        $this->service->setAuthorizeRequestFactory($factory);
        
        $this->assertSame($authorizeRequest, $this->service->createAuthorizeRequest($httpRequest));
    }


    public function testCreateHttpResponse()
    {
        $result = $this->getMockBuilder('InoOicServer\Oic\Authorize\Result')
            ->disableOriginalConstructor()
            ->getMock();
        $httpResponse = $this->service->createHttpResponse($result);
        
        $this->assertInstanceOf('Zend\Http\Response', $httpResponse);
    }
}