<?php

namespace PhpIdServer\ServiceManager;

use Zend\ServiceManager\Config;
use PhpIdServer\Controller\IndexController;
use Zend\Mvc\Controller\ControllerManager;
use PhpIdServer\Controller\DiscoveryController;
use PhpIdServer\Controller\AuthorizeController;
use PhpIdServer\Controller\TokenController;
use PhpIdServer\Controller\UserinfoController;


class ControllerManagerConfig extends Config
{


    public function getAbstractFactories()
    {
        return array(
            'PhpIdServer\Authentication\Controller\ControllerAbstractFactory'
        );
    }


    public function getFactories()
    {
        return array(
            'PhpIdServer\IndexController' => function (ControllerManager $controllerManager)
            {
                $controller = new IndexController();
                return $controller;
            },
            
            'PhpIdServer\DiscoveryController' => function (ControllerManager $controllerManager)
            {
                $controller = new DiscoveryController();
                return $controller;
            },
            
            'PhpIdServer\AuthorizeController' => function (ControllerManager $controllerManager)
            {
                $sm = $controllerManager->getServiceLocator();
                
                $controller = new AuthorizeController();
                $controller->setLogger($sm->get('PhpIdServer\Logger'));
                $controller->setAuthorizeContextManager($sm->get('PhpIdServer\AuthorizeContextManager'));
                $controller->setAuthorizeDispatcher($sm->get('PhpIdServer\AuthorizeDispatcher'));
                $controller->setAuthenticationManager($sm->get('PhpIdServer\AuthenticationManager'));
                
                return $controller;
            },
            
            'PhpIdServer\TokenController' => function (ControllerManager $controllerManager)
            {
                $sm = $controllerManager->getServiceLocator();
                
                $controller = new TokenController();
                $controller->setLogger($sm->get('PhpIdServer\Logger'));
                $controller->setDispatcher($sm->get('PhpIdServer\TokenDispatcher'));
                
                return $controller;
            },
            
            'PhpIdServer\UserinfoController' => function (ControllerManager $controllerManager)
            {
                $sm = $controllerManager->getServiceLocator();
                
                $controller = new UserinfoController();
                $controller->setLogger($sm->get('PhpIdServer\Logger'));
                $controller->setDispatcher($sm->get('PhpIdServer\UserInfoDispatcher'));
                
                return $controller;
            }
        );
    }
}