<?php

namespace InoOicServerTest\Oic\Authorize\Http;

use InoOicServer\Oic\Error;
use InoOicServer\Oic\Authorize\Response\ClientErrorResponse;
use InoOicServer\Oic\Authorize\Result;
use InoOicServer\Oic\Authorize\Http\HttpService;
use InoOicServer\Oic\Authorize\Redirect;


class HttpServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var HttpService
     */
    protected $service;


    public function setUp()
    {
        $this->service = new HttpService(array(), $this->createAuthenticationManagerMock());
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


    public function testCreateHttpResponseFromRedirectToAuthentication()
    {
        $authUrl = 'https://auth/url';
        $redirect = new Redirect(Redirect::TO_AUTHENTICATION);
        
        $authManager = $this->createAuthenticationManagerMock();
        $authManager->expects($this->once())
            ->method('getAuthenticationUrl')
            ->will($this->returnValue($authUrl));
        $this->service->setAuthenticationManager($authManager);
        
        $httpResponse = $this->service->createHttpResponseFromRedirectToAuthentication($redirect);
        $this->assertSame(302, $httpResponse->getStatusCode());
        $this->assertSame($authUrl, $httpResponse->getHeaders()
            ->get('Location')
            ->getUri());
    }


    public function testCreateHttpResponseFromRedirectToResponse()
    {
        $returnUrl = 'https://response/url';
        $redirect = new Redirect(Redirect::TO_RESPONSE);
        
        $authManager = $this->createAuthenticationManagerMock();
        $authManager->expects($this->once())
            ->method('getReturnUrl')
            ->will($this->returnValue($returnUrl));
        $this->service->setAuthenticationManager($authManager);
        
        $httpResponse = $this->service->createHttpResponseFromRedirectToResponse($redirect);
        $this->assertSame(302, $httpResponse->getStatusCode());
        $this->assertSame($returnUrl, $httpResponse->getHeaders()
            ->get('Location')
            ->getUri());
    }


    public function testCreateHttpResponseFromRedirectToUrl()
    {
        $url = 'https://custom/url';
        $redirect = new Redirect(Redirect::TO_URL);
        $redirect->setUrl($url);
        
        $httpResponse = $this->service->createHttpResponseFromRedirectToUrl($redirect);
        $this->assertSame(302, $httpResponse->getStatusCode());
        $this->assertSame($url, $httpResponse->getHeaders()
            ->get('Location')
            ->getUri());
    }
    
    /*
     *
     */
    protected function createAuthenticationManagerMock()
    {
        $manager = $this->getMockBuilder('InoOicServer\Oic\User\Authentication\Manager')
            ->disableOriginalConstructor()
            ->getMock();
        
        return $manager;
    }
}