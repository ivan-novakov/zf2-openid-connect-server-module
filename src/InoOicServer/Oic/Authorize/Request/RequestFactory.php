<?php

namespace InoOicServer\Oic\Authorize\Request;

use Zend\Stdlib\ArrayUtils;
use Zend\Http\Header\HeaderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Http;
use InoOicServer\Util\OptionsTrait;


class RequestFactory implements RequestFactoryInterface
{
    
    use OptionsTrait;

    const OPT_AUTH_COOKIE_NAME = 'auth_cookie_name';

    /**
     * @var array
     */
    protected $defaultOptions = array(
        self::OPT_AUTH_COOKIE_NAME => 'oic_auth'
    );

    /**
     * @var array
     */
    protected $paramNames = array(
        Params::CLIENT_ID,
        Params::REDIRECT_URI,
        Params::RESPONSE_TYPE,
        Params::SCOPE,
        Params::STATE
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
     * {@inhertidoc}
     * @see \InoOicServer\Oic\Authorize\Request\RequestFactoryInterface::createRequest()
     */
    public function createRequest(Http\Request $httpRequest)
    {
        $request = new Request();
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
        $cookieHeader = $httpRequest->getCookie();
        if ($cookieHeader instanceof HeaderInterface) {
            $value = $cookieHeader->offsetGet($this->getOption(self::OPT_AUTH_COOKIE_NAME));
            $request->setAuthenticationSessionId($value);
        }
        
        return $request;
    }
}