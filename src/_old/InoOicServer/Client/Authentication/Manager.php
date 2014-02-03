<?php

namespace InoOicServer\Client\Authentication;

use InoOicServer\OpenIdConnect\Request\RequestInterface;
use InoOicServer\Client\Authentication\Method\MethodFactoryInterface;
use InoOicServer\Client\Authentication\Method\MethodFactory;
use InoOicServer\Client\Client;
use InoOicServer\Util\Options;


/**
 * The authentication manager authenticates the client using the client request data and the client info from the
 * local registry.
 */
class Manager
{

    const OPT_METHODS = 'methods';

    /**
     * Options.
     * @var Options
     */
    protected $options;

    /**
     * The authentication method factory.
     * 
     * @var MethodFactoryInterface
     */
    protected $methodFactory;


    /**
     * Constructor.
     * 
     * @param array|\Traversable $options
     */
    public function __construct($options = array())
    {
        $this->setOptions($options);
    }


    /**
     * Sets the options.
     * 
     * @param array|\Traversable $options
     */
    public function setOptions($options = array())
    {
        $this->options = new Options($options);
    }


    /**
     * Returns the options.
     * 
     * @return Options
     */
    public function getOptions()
    {
        return $this->options;
    }


    /**
     * Sets the authentication method factory.
     * 
     * @param MethodFactoryInterface $methodFactory
     */
    public function setAuthenticationMethodFactory(MethodFactoryInterface $methodFactory)
    {
        $this->methodFactory = $methodFactory;
    }


    /**
     * Returns the authentication method factory.
     * 
     * @return MethodFactoryInterface
     */
    public function getAuthenticationMethodFactory()
    {
        if (! ($this->methodFactory instanceof MethodFactoryInterface)) {
            $this->methodFactory = new MethodFactory();
        }
        
        return $this->methodFactory;
    }


    /**
     * Authenticates the client - uses the client's configured authentication method and authenticates
     * the request.
     * 
     * @param RequestInterface $request
     * @param Client $client
     * @return Result
     */
    public function authenticate(RequestInterface $request, Client $client)
    {
        $clientAuthenticationInfo = $client->getAuthenticationInfo();
        $authenticationMethod = $clientAuthenticationInfo->getMethod();
        $authenticationMethodConfig = $this->getMethodConfig($authenticationMethod);
        $method = $this->getAuthenticationMethodFactory()->createAuthenticationMethod($authenticationMethodConfig);
        // $method = $this->getAuthenticationMethodFactory()->createMethod($clientAuthenticationInfo->getMethod());
        return $method->authenticate($clientAuthenticationInfo, $request->getHttpRequest());
    }


    protected function getMethodConfig($methodName)
    {
        $methods = $this->options->get(self::OPT_METHODS, array());
        if (! isset($methods[$methodName]) || ! is_array($methods[$methodName])) {
            throw new Method\Exception\InvalidAuthenticationMethodException($methodName);
        }
        
        return $methods[$methodName];
    }
}