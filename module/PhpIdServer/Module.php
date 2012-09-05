<?php
namespace PhpIdServer;
use Zend\Config\Config;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;


class Module implements AutoloaderProviderInterface
{


    public function getAutoloaderConfig ()
    {
        return array(
            
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php'
            ), 
            
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/', __NAMESPACE__)
                )
            )
        );
    }


    public function getConfig ()
    {
        return include __DIR__ . '/config/module.config.php';
    }


    public function onBootstrap (MvcEvent $e)
    {
        $serverConfig = new Config(require __DIR__ . '/config/server.config.php');
        $e->getApplication()->getServiceManager()->setService('serverConfig', $serverConfig);
        
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager = $e->getApplication()
            ->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
}
