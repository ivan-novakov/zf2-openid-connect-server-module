<?php

namespace InoOicServer\ServiceManager;

use Zend\ServiceManager\Config;
use Zend\Mvc\Controller\ControllerManager;
use InoOicServer\Controller\IndexController;
use InoOicServer\Controller\DiscoveryController;
use InoOicServer\Controller\AuthorizeController;
use InoOicServer\Controller\TokenController;
use InoOicServer\Controller\UserinfoController;
use InoOicServer\Controller\JwksController;


class ControllerManagerConfig extends Config
{


    public function getAbstractFactories()
    {
        return array(
            'InoOicServer\Authentication\Controller\ControllerAbstractFactory'
        );
    }


    public function getFactories()
    {
        return array(
            'InoOicServer\IndexController' => function (ControllerManager $controllerManager)
            {
                $controller = new IndexController();
                return $controller;
            },
            
            'InoOicServer\DiscoveryController' => function (ControllerManager $controllerManager)
            {
                $sm = $controllerManager->getServiceLocator();
                
                $controller = new DiscoveryController();
                $controller->setServerInfo($sm->get('InoOicServer\ServerInfo'));
                return $controller;
            },
            
            'InoOicServer\JwksController' => function (ControllerManager $controllerManager)
            {
                
                $controller = new JwksController();
                
                return $controller;
            },
            
            'InoOicServer\AuthorizeController' => function (ControllerManager $controllerManager)
            {
                $sm = $controllerManager->getServiceLocator();
                
                $controller = new AuthorizeController();
                $controller->setLogger($sm->get('InoOicServer\Logger'));
                $controller->setAuthorizeContextManager($sm->get('InoOicServer\AuthorizeContextManager'));
                $controller->setAuthorizeDispatcher($sm->get('InoOicServer\AuthorizeDispatcher'));
                $controller->setAuthenticationManager($sm->get('InoOicServer\AuthenticationManager'));
                $controller->setSessionContainer($sm->get('InoOicServer\SessionContainer'));
                
                return $controller;
            },
            
            'InoOicServer\TokenController' => function (ControllerManager $controllerManager)
            {
                $sm = $controllerManager->getServiceLocator();
                
                $controller = new TokenController();
                $controller->setLogger($sm->get('InoOicServer\Logger'));
                $controller->setDispatcher($sm->get('InoOicServer\TokenDispatcher'));
                
                return $controller;
            },
            
            'InoOicServer\UserinfoController' => function (ControllerManager $controllerManager)
            {
                $sm = $controllerManager->getServiceLocator();
                
                $controller = new UserinfoController();
                $controller->setLogger($sm->get('InoOicServer\Logger'));
                $controller->setDispatcher($sm->get('InoOicServer\UserInfoDispatcher'));
                
                return $controller;
            }
        );
    }
}