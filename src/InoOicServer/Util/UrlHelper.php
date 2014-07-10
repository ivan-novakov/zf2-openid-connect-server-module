<?php

namespace InoOicServer\Util;

use Zend\Mvc\Router\Http\TreeRouteStack;


class UrlHelper
{

    /**
     * @var TreeRouteStack
     */
    protected $router;


    /**
     * Constructor.
     * 
     * @param TreeRouteStack $router
     */
    public function __construct(TreeRouteStack $router)
    {
        $this->setRouter($router);
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
     * Assembles a full URL based on the provided route name and parameters.
     * 
     * @param string $routeName
     * @param array $params
     * @param boolean $returnAsString
     * @return string|\Zend\Uri\Http
     */
    public function createUrlFromRoute($routeName, array $params = array(), $returnAsString = false)
    {
        $options = array(
            'name' => $routeName
        );
        
        $router = $this->getRouter();
        
        $path = $router->assemble($params, $options);
        
        $uri = $router->getRequestUri();
        $uri->setPath($path);
        
        if ($returnAsString) {
            return $uri->toString();
        }
        
        return $uri;
    }
}