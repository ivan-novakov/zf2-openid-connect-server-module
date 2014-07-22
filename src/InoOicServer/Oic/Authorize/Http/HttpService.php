<?php
namespace InoOicServer\Oic\Authorize\Http;

use Zend\Http;
use Zend\Uri;
use Zend\Session;
use InoOicServer\Oic\Authorize\Response\AuthorizeResponse;
use InoOicServer\Oic\Authorize\Response\AuthorizeErrorResponse;
use InoOicServer\Oic\Authorize\Response\ClientErrorResponse;
use InoOicServer\Oic\Authorize\Response\ResponseInterface;
use InoOicServer\Oic\Authorize\Redirect;
use InoOicServer\Oic\Authorize\Result;
use InoOicServer\Oic\Authorize\Params;
use InoOicServer\Oic\Authorize\AuthorizeRequestFactoryInterface;
use InoOicServer\Oic\Authorize\AuthorizeRequestFactory;
use InoOicServer\Util\OptionsTrait;
use InoOicServer\Oic\User\Authentication\Manager;
use InoOicServer\Util\CookieManager;
use Zend\Http\Header\SetCookie;

class HttpService implements HttpServiceInterface
{
    
    use OptionsTrait;

    const OPT_AUTH_COOKIE_NAME = 'auth_cookie_name';

    const OPT_SESSION_COOKIE_NAME = 'session_cookie_name';

    /**
     * @var CookieManager
     */
    protected $cookieManager;

    /**
     * @var Manager
     */
    protected $authenticationManager;

    /**
     * @var AuthorizeRequestFactoryInterface
     */
    protected $authorizeRequestFactory;

    /**
     * @var Session\Container
     */
    protected $sessionContainer;

    /**
     * @var array
     */
    protected $defaultOptions = array(
        self::OPT_AUTH_COOKIE_NAME => 'oic_auth',
        self::OPT_SESSION_COOKIE_NAME => 'oic_session'
    );

    /**
     * @var array
    */
    protected $paramNames = array(
        Params::CLIENT_ID,
        Params::REDIRECT_URI,
        Params::RESPONSE_TYPE,
        Params::SCOPE,
        Params::STATE,
        Params::NONCE
    );

    /**
     * @var array
     */
    protected $responseHandlers = array(
        'InoOicServer\Oic\Authorize\Response\ClientErrorResponse' => 'createHttpResponseFromClientError',
        'InoOicServer\Oic\Authorize\Response\AuthorizeErrorResponse' => 'createHttpResponseFromAuthorizeError',
        'InoOicServer\Oic\Authorize\Response\AuthorizeResponse' => 'createHttpResponseFromAuthorizeResponse'
    );

    /**
     * @var array
     */
    protected $redirectHandlers = array(
        Redirect::TO_AUTHENTICATION => 'createHttpResponseFromRedirectToAuthentication',
        Redirect::TO_RESPONSE => 'createHttpResponseFromRedirectToResponse',
        Redirect::TO_URL => 'createHttpResponseFromRedirectToUrl'
    );

    /**
     * Constructor.
     *
     * @param array $options
     * @param Manager $authenticationManager
     */
    public function __construct(array $options = array(), Manager $authenticationManager, Session\Container $sessionContainer)
    {
        $this->setOptions($options);
        $this->setAuthenticationManager($authenticationManager);
        $this->setSessionContainer($sessionContainer);
    }

    /**
     * @return Manager
     */
    public function getAuthenticationManager()
    {
        return $this->authenticationManager;
    }

    /**
     * @param Manager $authenticationManager
     */
    public function setAuthenticationManager(Manager $authenticationManager)
    {
        $this->authenticationManager = $authenticationManager;
    }

    /**
     * @return CookieManager
     */
    public function getCookieManager()
    {
        if (! $this->cookieManager instanceof CookieManager) {
            $this->cookieManager = new CookieManager();
        }
        
        return $this->cookieManager;
    }

    /**
     * @param CookieManager $cookieManager
     */
    public function setCookieManager(CookieManager $cookieManager)
    {
        $this->cookieManager = $cookieManager;
    }

    /**
     * @return AuthorizeRequestFactoryInterface
     */
    public function getAuthorizeRequestFactory()
    {
        if (! $this->authorizeRequestFactory instanceof AuthorizeRequestFactoryInterface) {
            $this->authorizeRequestFactory = new AuthorizeRequestFactory();
        }
        
        return $this->authorizeRequestFactory;
    }

    /**
     * @param AuthorizeRequestFactoryInterface $authorizeRequestFactory
     */
    public function setAuthorizeRequestFactory(AuthorizeRequestFactoryInterface $authorizeRequestFactory)
    {
        $this->authorizeRequestFactory = $authorizeRequestFactory;
    }

    /**
     * @return \Zend\Session\Container
     */
    public function getSessionContainer()
    {
        return $this->sessionContainer;
    }

    /**
     * @param \Zend\Session\Container $sessionContainer
     */
    public function setSessionContainer(Session\Container $sessionContainer)
    {
        $this->sessionContainer = $sessionContainer;
    }

    /**
     * {@inhertidoc}
     * @see \InoOicServer\Oic\Authorize\Http\HttpServiceInterface::createAuthorizeRequest()
     */
    public function createAuthorizeRequest(Http\Request $httpRequest)
    {
        $data = array(
            'http_request' => $httpRequest
        );
        
        /*
         * GET params
         */
        foreach ($this->paramNames as $paramName) {
            $paramValue = $httpRequest->getQuery($paramName);
            if (null !== $paramValue) {
                $data[$paramName] = $paramValue;
            }
        }
        
        /*
         * Headers
         */
        /*
        $cookieManager = $this->getCookieManager();
        
        $sessionId = $cookieManager->getCookieValue($httpRequest, $this->getOption(self::OPT_SESSION_COOKIE_NAME));
        if (null !== $sessionId) {
            $data['session_id'] = $sessionId;
        }
        
        $authenticationSessionId = $cookieManager->getCookieValue($httpRequest, $this->getOption(self::OPT_AUTH_COOKIE_NAME));
        if (null !== $authenticationSessionId) {
            $data['authentication_session_id'] = $authenticationSessionId;
        }
        */
        
        //TEST
        $sessionContainer = $this->getSessionContainer();
        if ($sessionContainer->offsetExists('session_id')) {
            $data['session_id'] = $sessionContainer->offsetGet('session_id');
        }
        
        if ($sessionContainer->offsetExists('authentication_session_id')) {
            $data['authentication_session_id'] = $sessionContainer->offsetGet('authentication_session_id');
        }

        return $this->getAuthorizeRequestFactory()->createRequest($data);
    }

    /**
     * {@inhertidoc}
     * @see \InoOicServer\Oic\Authorize\Http\HttpServiceInterface::createHttpResponse()
     */
    public function createHttpResponse(Result $result)
    {
        if ($result->getType() === Result::TYPE_REDIRECT) {
            return $this->createHttpResponseFromRedirect($result->getRedirect());
        }
        
        $httpResponse = $this->createHttpResponseFromResponse($result->getResponse());
        
        return $httpResponse;
    }

    /**
     * @param Redirect $redirect
     * @throws \RuntimeException
     * @return Http\Response
     */
    protected function createHttpResponseFromRedirect(Redirect $redirect)
    {
        $redirectType = $redirect->getType();
        if (! isset($this->redirectHandlers[$redirectType])) {
            throw new \RuntimeException(sprintf("Unknown redirect type '%s'", $redirectType));
        }
        
        $redirectHandler = $this->redirectHandlers[$redirectType];
        if (! method_exists($this, $redirectHandler)) {
            throw new \RuntimeException(sprintf("Non-existent redirect handler '%s' for redirect type '%s", $redirectHandler, $redirectType));
        }
        
        return call_user_func(array(
            $this,
            $redirectHandler
        ), $redirect);
    }

    /**
     * @param Redirect $redirect
     * @return Http\Response
     */
    protected function createHttpResponseFromRedirectToAuthentication(Redirect $redirect)
    {
        $redirectUrl = $this->getAuthenticationManager()->getAuthenticationUrl();
        $response = $this->createRedirectHttpResponse($redirectUrl);
        
        return $response;
    }

    /**
     * @param Redirect $redirect
     * @return Http\Response
     */
    protected function createHttpResponseFromRedirectToResponse(Redirect $redirect)
    {
        $redirectUrl = $this->getAuthenticationManager()->getReturnUrl();
        $response = $this->createRedirectHttpResponse($redirectUrl);
        
        return $response;
    }

    /**
     * @param Redirect $redirect
     * @return Http\Response
     */
    protected function createHttpResponseFromRedirectToUrl(Redirect $redirect)
    {
        $redirectUrl = $redirect->getUrl();
        $response = $this->createRedirectHttpResponse($redirectUrl);
        
        return $response;
    }

    /**
     * @param ResponseInterface $response
     * @throws \RuntimeException
     * @return Http\Response
     */
    protected function createHttpResponseFromResponse(ResponseInterface $response)
    {
        $responseClass = get_class($response);
        if (! isset($this->responseHandlers[$responseClass])) {
            throw new \RuntimeException(sprintf("Unknown response class '%s'", $responseClass));
        }
        
        $responseHandler = $this->responseHandlers[$responseClass];
        if (! method_exists($this, $responseHandler)) {
            throw new \RuntimeException(sprintf("Non-existent response handler '%s' for response class '%s'", $responseHandler, $responseClass));
        }
        
        return call_user_func(array(
            $this,
            $responseHandler
        ), $response);
    }

    /**
     * @param ClientErrorResponse $clientErrorResponse
     * @return Http\Response
     */
    protected function createHttpResponseFromClientError(ClientErrorResponse $clientErrorResponse)
    {
        $error = $clientErrorResponse->getError();
        
        $httpResponse = new Http\Response();
        $httpResponse->setStatusCode(400);
        $httpResponse->setContent(sprintf("Client error '%s' (%s)", $error->getMessage(), $error->getDescription()));
        
        return $httpResponse;
    }

    /**
     * @param AuthorizeErrorResponse $authorizeErrorResponse
     * @return Http\Response
     */
    protected function createHttpResponseFromAuthorizeError(AuthorizeErrorResponse $authorizeErrorResponse)
    {
        $error = $authorizeErrorResponse->getError();
        $redirectUri = $authorizeErrorResponse->getRedirectUri();
        
        $httpResponse = new Http\Response();
        $httpResponse->setStatusCode(302);
        
        $uri = new Uri\Http($redirectUri);
        $uri->setQuery(array(
            Params::STATE => $authorizeErrorResponse->getState(),
            Params::ERROR => $error->getMessage(),
            Params::ERROR_DESCRIPTION => $error->getDescription()
        ));
        
        $httpResponse->getHeaders()->addHeaders(array(
            'Location' => $uri->toString()
        ));
        
        return $httpResponse;
    }

    /**
     * @param AuthorizeResponse $authorizeResponse
     * @return Http\Response
     */
    protected function createHttpResponseFromAuthorizeResponse(AuthorizeResponse $authorizeResponse)
    {
        $httpResponse = new Http\Response();
        $httpResponse->setStatusCode(302);
        $httpResponse->setContent(sprintf("code: %s --> %s", $authorizeResponse->getCode(), $authorizeResponse->getRedirectUri()));
        
        $uri = new Uri\Http($authorizeResponse->getRedirectUri());
        $uri->setQuery(array(
            Params::CODE => $authorizeResponse->getCode(),
            Params::STATE => $authorizeResponse->getState()
        ));
        $httpResponse->getHeaders()->addHeaders(array(
            'Location' => $uri->toString()
        ));
        
        /*
        $httpResponse->getHeaders()->addHeaders(array(
            $this->createSetCookieHeader($this->getOption(self::OPT_AUTH_COOKIE_NAME), $authorizeResponse->getAuthSessionId()),
            $this->createSetCookieHeader($this->getOption(self::OPT_SESSION_COOKIE_NAME), $authorizeResponse->getSessionId())
        ));
        */
        // TEST
        $sessionContainer = $this->getSessionContainer();
        $sessionContainer->offsetSet('session_id', $authorizeResponse->getSessionId());
        $sessionContainer->offsetSet('authentication_session_id', $authorizeResponse->getAuthSessionId());
        
        return $httpResponse;
    }

    /**
     * @param string $redirectUrl
     * @param integer $statusCode
     * @return Http\Response
     */
    protected function createRedirectHttpResponse($redirectUrl, $statusCode = 302)
    {
        $locationHeader = new Http\Header\Location();
        $locationHeader->setUri($redirectUrl);
        
        $response = new Http\Response();
        $response->getHeaders()->addHeader($locationHeader);
        $response->setStatusCode($statusCode);
        
        return $response;
    }

    protected function createSetCookieHeader($name, $value)
    {
        // FIXME
        $setCookie = new SetCookie($name, $value, null, '/', null, false, true);
        
        return $setCookie;
    }
}