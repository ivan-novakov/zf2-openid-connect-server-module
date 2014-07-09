<?php

namespace InoOicServer\Oic\Authorize;

use Zend\Http;
use Zend\Stdlib\Hydrator\ClassMethods;
use InoOicServer\Util\OptionsTrait;
use InoOicServer\Util\CookieManager;


class AuthorizeRequestFactory implements AuthorizeRequestFactoryInterface
{
    
    use OptionsTrait;

    const OPT_AUTH_COOKIE_NAME = 'auth_cookie_name';

    const OPT_SESSION_COOKIE_NAME = 'session_cookie_name';

    /**
     * @var CookieManager
     */
    protected $cookieManager;

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
     * Constructor.
     * 
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->setOptions($options);
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
     * {@inhertidoc}
     * @see \InoOicServer\Oic\Authorize\Request\RequestFactoryInterface::createRequest()
     */
    public function createRequest(Http\Request $httpRequest)
    {
        $request = new AuthorizeRequest();
        $request->setHttpRequest($httpRequest);
        
        /*
         * GET params
         */
        $params = array();
        foreach ($this->paramNames as $paramName) {
            $paramValue = $httpRequest->getQuery($paramName);
            if (null !== $paramValue) {
                $params[$paramName] = $paramValue;
            }
        }
        
        $hydrator = new ClassMethods();
        $hydrator->hydrate($params, $request);
        
        /*
         * Headers
         */
        $cookieManager = $this->getCookieManager();
        
        $sessionId = $cookieManager->getCookieValue($httpRequest, $this->getOption(self::OPT_SESSION_COOKIE_NAME));
        if (null !== $sessionId) {
            $request->setSessionId($sessionId);
        }
        
        $authenticationSessionId = $cookieManager->getCookieValue($httpRequest, $this->getOption(self::OPT_AUTH_COOKIE_NAME));
        if (null !== $authenticationSessionId) {
            $request->setAuthenticationSessionId($authenticationSessionId);
        }
        
        return $request;
    }
}