<?php

namespace InoOicServer\ServiceManager;

use Zend\ServiceManager\Config;
use Zend\Mvc\Controller\ControllerManager;
use InoOicServer\Mvc\Controller;


class ControllerManagerConfig extends Config
{


    /*
    public function getAbstractFactories()
    {
        return array(
            'InoOicServer\Authentication\Controller\ControllerAbstractFactory'
        );
    }
    */

    public function getFactories()
    {
        return array(
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
            
            'InoOicServer\AuthorizeController' => function (ControllerManager $controllerManager)
            {
                $sm = $controllerManager->getServiceLocator();
                
                $controller = new Controller\AuthorizeController();
                
                return $controller;
            },
            
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
        );
    }
}