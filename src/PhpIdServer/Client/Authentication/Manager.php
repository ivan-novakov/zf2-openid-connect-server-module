<?php

namespace PhpIdServer\Client\Authentication;

use PhpIdServer\OpenIdConnect\Request\ClientRequestInterface;
use PhpIdServer\Client\Authentication\Method\MethodFactory;
use PhpIdServer\Client\Client;
use PhpIdServer\Util\Options;


/**
 * The authentication manager authenticates the client using the client request data and the client info from the
 * local registry.
 *
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class Manager
{

    const OPT_METHODS = 'methods';

    /**
     * Options.
     * @var Options
     */
    protected $_options = null;

    /**
     * The authentication method factory.
     * 
     * @var MethodFactory
     */
    protected $_methodFactory = null;


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
        $this->_options = new Options($options);
    }


    /**
     * Sets the authentication method factory.
     * 
     * @param MethodFactory $methodFactory
     */
    public function setAuthenticationMethodFactory(MethodFactory $methodFactory)
    {
        $this->_methodFactory = $methodFactory;
    }


    /**
     * Returns the authentication method factory.
     * 
     * @return MethodFactory
     */
    public function getAuthenticationMethodFactory()
    {
        if (! ($this->_methodFactory instanceof MethodFactory)) {
            $methods = $this->_options->get(self::OPT_METHODS, array());
            $this->_methodFactory = new MethodFactory($methods);
        }
        
        return $this->_methodFactory;
    }


    /**
     * Authenticates the client - uses the client's configured authentication method and authenticates
     * the request.
     * 
     * @param ClientRequestInterface $request
     * @param Client $client
     * @return Result
     */
    public function authenticate(ClientRequestInterface $request, Client $client)
    {
        $clientAuthenticationInfo = $client->getAuthenticationInfo();
        if ('dummy' == $clientAuthenticationInfo->getMethod()) {
            return new Result(true);
        }
        
        $clientAuthenticationData = $request->getAuthenticationData();        
        if ($clientAuthenticationInfo->getMethod() != $clientAuthenticationData->getMethod()) {
            return new Result(false, 'invalid authentication method');
        }
        
        $method = $this->getAuthenticationMethodFactory()
            ->createMethod($clientAuthenticationInfo->getMethod());
        
        return $method->authenticate($clientAuthenticationInfo, $clientAuthenticationData);
    }
}