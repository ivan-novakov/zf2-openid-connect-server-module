<?php

namespace PhpIdServer;

use Zend\Mvc\MvcEvent;
use Zend\EventManager\EventInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;


class Module implements AutoloaderProviderInterface, BootstrapListenerInterface, ServiceProviderInterface
{


    public function getAutoloaderConfig()
    {
        return array(
            
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/', __NAMESPACE__)
                )
            )
        );
    }


    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }


    public function onBootstrap(EventInterface $e)
    {
        /* @var $e MvcEvent */
        $eventManager = $e->getApplication()
            ->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        //$eventManager->clearListeners(MvcEvent::EVENT_DISPATCH_ERROR);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, function (MvcEvent $e)
        {
            _dump('ERROR IN DISPATCH: ' . $e->getError());
        }, 100);
    }


    public function getServiceConfig()
    {
        //return '\PhpIdServer\ServiceManager\ServiceManagerConfig';
        return new \PhpIdServer\ServiceManager\ServiceManagerConfig();
    }
}
