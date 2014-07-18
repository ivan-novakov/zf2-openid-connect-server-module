<?php

namespace InoOicServerTest\Oic\Authorize\Http;

use Zend\Http;
use Zend\Uri;
use InoOicServer\Oic\Error;
use InoOicServer\Oic\Authorize\Response\ClientErrorResponse;
use InoOicServer\Oic\Authorize\Result;
use InoOicServer\Oic\Authorize\Http\HttpService;
use InoOicServer\Oic\Authorize\Redirect;
use InoOicServer\Oic\Authorize\Response\AuthorizeErrorResponse;


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
        $authCookieName = 'foocookie';
        $authSessionId = '123abc';
        $sessionCookieName = 'barcookie';
        $sessionId = '456asd';

        $params = array(
            'client_id' => 'testclient',
            'redirect_uri' => 'https://redirect/',
            'response_type' => 'foo',
            'scope' => 'bar',
            'state' => '123456',
            'nonce' => 'testnonce'
        );

        $httpRequest = new Http\Request();
        $httpRequest->getQuery()->fromArray($params);
        $httpRequest->getHeaders()->addHeader(new Http\Header\Cookie(array(
            $authCookieName => $authSessionId,
            $sessionCookieName => $sessionId
        )));

        $this->service->setOptions(array(
            HttpService::OPT_AUTH_COOKIE_NAME => $authCookieName,
            HttpService::OPT_SESSION_COOKIE_NAME => $sessionCookieName
        ));
        $request = $this->service->createAuthorizeRequest($httpRequest);

        $this->assertInstanceOf('InoOicServer\Oic\Authorize\AuthorizeRequest', $request);
        $this->assertSame($params['client_id'], $request->getClientId());
        $this->assertSame($params['redirect_uri'], $request->getRedirectUri());
        $this->assertSame($params['response_type'], $request->getResponseType());
        $this->assertSame($params['scope'], $request->getScope());
        $this->assertSame($params['state'], $request->getState());
        $this->assertSame($params['nonce'], $request->getNonce());

        $this->assertSame($httpRequest, $request->getHttpRequest());

        $this->assertSame($authSessionId, $request->getAuthenticationSessionId());
        $this->assertSame($sessionId, $request->getSessionId());
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


    public function testCreateHttpResponseFromAuthorizeErrorResponse()
    {
        $redirectUri = 'https://redirect.org:1234/return/path';
        $errorMessage = 'dummy error';
        $errorDescription = 'dummy error desc';
        $state = 'dummy_state';

        $expectedUri = new Uri\Http($redirectUri);
        $expectedUri->setQuery(array(
            'error' => $errorMessage,
            'error_description' => $errorDescription
        ));

        $response = new AuthorizeErrorResponse();
        $response->setError(new Error($errorMessage, $errorDescription));
        $response->setRedirectUri($redirectUri);
        $response->setState($state);

        $result = Result::constructResponseResult($response);

        $httpResponse = $this->service->createHttpResponse($result);
        /* @var $httpResponse \Zend\Http\Response */
        $this->assertInstanceOf('Zend\Http\Response', $httpResponse);
        $this->assertSame(302, $httpResponse->getStatusCode());

        $locationHeader = $httpResponse->getHeaders()->get('Location');
        $this->assertInstanceOf('Zend\Http\Header\Location', $locationHeader);

        /* @var $locationHeader \Zend\Http\Header\Location */
        $uri = $locationHeader->uri();

        $this->assertSame('https', $uri->getScheme());
        $this->assertSame('redirect.org', $uri->getHost());
        $this->assertSame(1234, $uri->getPort());
        $this->assertSame('/return/path', $uri->getPath());
        $this->assertEquals(array(
            'error' => $errorMessage,
            'error_description' => $errorDescription,
            'state' => $state
        ), $uri->getQueryAsArray());
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