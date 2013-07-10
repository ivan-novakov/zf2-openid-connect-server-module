<?php

namespace PhpIdServer;

use Zend\Mvc\MvcEvent;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;


class Module implements AutoloaderProviderInterface, BootstrapListenerInterface, ServiceProviderInterface, ControllerProviderInterface
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
        $application = $e->getTarget();
        /* @var $e MvcEvent */
        $eventManager = $application->getEventManager();
        
        $services = $application->getServiceManager();
        $eventManager->attach('dispatch.error', function ($event) use($services)
        {
            $exception = $event->getResult()->exception;
            $error = $event->getError();
            if (! $exception && ! $error) {
                return;
            }
            
            $service = $services->get('PhpIdServer\ErrorHandler');
            if ($exception) {
                $service->logException($exception);
            }
            
            if ($error) {
                $service->logError('Dispatch ERROR: ' . $error);
            }
        });
    }


    public function getServiceConfig()
    {
        return new ServiceManager\ServiceManagerConfig();
    }


    public function getControllerConfig()
    {
        return new ServiceManager\ControllerManagerConfig();
    }
}


function _dump($value)
{
    error_log(print_r($value, true));
}
