<?php

namespace InoOicServer\Oic\User\Authentication;

use InoOicServer\Exception\MissingOptionException;
use Zend\Mvc\Router\Http\TreeRouteStack;
use InoOicServer\Util\OptionsTrait;


class Manager
{
    
    use OptionsTrait;

    const OPT_METHOD = 'method';

    const OPT_AUTH_ROUTE = 'auth_route';

    const OPT_RETURN_ROUTE = 'return_route';

    /**
     * @var TreeRouteStack
     */
    protected $router;


    public function __construct(array $options, TreeRouteStack $router)
    {
        $this->setOptions($options);
    }


    /**
     * @return TreeRouteStack
     */
    public function getRouter()
    {
        return $this->router;
    }


    /**
     * @param TreeRouteStack $router
     */
    public function setRouter(TreeRouteStack $router)
    {
        $this->router = $router;
    }


    /**
     * Returns the configured user authentication method.
     * 
     * @return string
     */
    public function getAuthenticationMethod()
    {
        return $this->getOption(self::OPT_METHOD);
    }


    /**
     * Returns the corresponding full authentication URL.
     * 
     * @throws MissingOptionException
     * @return string
     */
    public function getAuthenticationUrl()
    {
        $methodName = $this->getAuthenticationMethod();
        if (null === $methodName) {
            throw new MissingOptionException(self::OPT_METHOD);
        }
        
        $authRoute = $this->getOption(self::OPT_AUTH_ROUTE);
        if (null === $authRoute) {
            throw new MissingOptionException(self::OPT_AUTH_ROUTE);
        }
        
        return $this->getRouter()->assemble(array(
            'controller' => $methodName
        ), array(
            'name' => $authRoute
        ));
    }


    /**
     * Returns the full URL the user will be returned to after authentication.
     * 
     * @throws MissingOptionException
     * @return string
     */
    public function getReturnUrl()
    {
        $returnRoute = $this->getOption(self::OPT_RETURN_ROUTE);
        if (null === $returnRoute) {
            throw new MissingOptionException(self::OPT_RETURN_ROUTE);
        }
        
        return $this->getRouter()->assemble(array(), array(
            'name' => $returnRoute
        ));
    }
}