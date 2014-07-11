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
     * Creates an Uri object based on the route name and the provided parameters.
     * 
     * @param string $routeName
     * @param array $params
     * @return \Zend\Uri\Http
     */
    public function createUriFromRoute($routeName, array $params = array())
    {
        $options = array(
            'name' => $routeName
        );
        
        $router = $this->getRouter();
        
        $path = $router->assemble($params, $options);
        
        $uri = $router->getRequestUri();
        $uri->setPath($path);
        
        return $uri;
    }


    /**
     * Assembles a full URL string based on the provided route name and parameters.
     * 
     * @param string $routeName
     * @param array $params
     * @return string
     */
    public function createUrlStringFromRoute($routeName, array $params = array())
    {
        return $this->createUriFromRoute($routeName, $params)->toString();
    }
}