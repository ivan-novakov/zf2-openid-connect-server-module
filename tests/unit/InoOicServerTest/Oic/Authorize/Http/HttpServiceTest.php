<?php

namespace InoOicServerTest\Oic\Authorize\Http;

use InoOicServer\Oic\Error;
use InoOicServer\Oic\Authorize\Response\ClientErrorResponse;
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
        $this->service = new HttpService(array());
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


    public function testCreateHttpResponseWithClientError()
    {
        $message = 'error_message';
        $description = 'error_description';
        
        $error = new Error();
        $error->setMessage($message);
        $error->setDescription($description);
        
        $response = new ClientErrorResponse();
        $response->setError($error);
        
        $result = Result::constructResponseResult($response);
        $httpResponse = $this->service->createHttpResponse($result);
        
        $this->assertInstanceOf('Zend\Http\Response', $httpResponse);
        $this->assertSame(400, $httpResponse->getStatusCode());
        $this->assertRegExp('/' . $message . '/', $httpResponse->getContent());
        $this->assertRegExp('/' . $description . '/', $httpResponse->getContent());
    }
}