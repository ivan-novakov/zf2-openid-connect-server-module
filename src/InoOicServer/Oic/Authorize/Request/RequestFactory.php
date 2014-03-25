<?php

namespace InoOicServer\Oic\Authorize\Request;

use Zend\Stdlib\ArrayUtils;
use InoOicServer\Util\Options;
use Zend\Http\Header\HeaderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Http;


class RequestFactory implements RequestFactoryInterface
{

    const OPT_AUTH_COOKIE_NAME = 'auth_cookie_name';

    /**
     * @var array
     */
    protected $options = array(
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
        $options = ArrayUtils::merge($this->options, $options);
        $this->setOptions($options);
    }


    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = new Options($options);
    }


    /**
     * @param string $optionName
     * @param mixed $defaultValue
     */
    public function getOption($optionName, $defaultValue = null)
    {
        return $this->options->get($optionName, $defaultValue);
    }


    /**
     * {@inhertidoc}
     * @see \InoOicServer\Oic\Authorize\Request\RequestFactoryInterface::createRequest()
     */
    public function createRequest(Http\Request $httpRequest)
    {
        $request = new Request();
        
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