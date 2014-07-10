<?php

namespace InoOicServer\ServiceManager;

use InoOicServer\Oic\Authentication\Controller\DummyController;
use InoOicServer\Oic\Authentication\Controller\BasicAuthController;
use Zend\ServiceManager\Config;
use Zend\Mvc\Controller\ControllerManager;
use InoOicServer\Mvc\Controller;


class ControllerManagerConfig extends Config
{


    public function getFactories()
    {
        return array(
            /*
            'InoOicServer\IndexController' => function (ControllerManager $controllerManager)
            {
                $controller = new Controller\IndexController();
                return $controller;
            },
            
            'InoOicServer\DiscoveryController' => function (ControllerManager $controllerManager)
            {
                $sm = $controllerManager->getServiceLocator();
                
                $controller = new Controller\DiscoveryController();
                $controller->setServerInfo($sm->get('InoOicServer\ServerInfo'));
                return $controller;
            },
            */
            'InoOicServer\Mvc\Controller\AuthorizeController' => function (ControllerManager $controllerManager)
            {
                $sm = $controllerManager->getServiceLocator();
                
                $authorizeService = $sm->get('InoOicServer\Oic\Authorize\AuthorizeService');
                $httpService = $sm->get('InoOicServer\Oic\Authorize\Http\HttpService');
                
                $controller = new Controller\AuthorizeController($httpService, $authorizeService);
                
                return $controller;
            },
            
            'InoOicServer\Oic\Authentication\Controller\BasicAuthController' => function (
                ControllerManager $controllerManager)
            {
                return new BasicAuthController();
            },
            
            'InoOicServer\Oic\Authentication\Controller\DummyController' => function (
                ControllerManager $controllerManager)
            {
                return new DummyController();
            },
            
            /*
            'InoOicServer\TokenController' => function (ControllerManager $controllerManager)
            {
                $sm = $controllerManager->getServiceLocator();
                
                $controller = new Controller\TokenController();
                
                return $controller;
            },
            
            'InoOicServer\UserinfoController' => function (ControllerManager $controllerManager)
            {
                $sm = $controllerManager->getServiceLocator();
                
                $controller = new Controller\UserinfoController();
                
                return $controller;
            }
            */
        );
    }
}