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
        $serviceManager = $e->getApplication()
            ->getServiceManager();
        
        $eventManager = $e->getApplication()
            ->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $router = $e->getRouter();
        //_dump($router);
        

        //$eventManager->clearListeners(MvcEvent::EVENT_DISPATCH_ERROR);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, function  (MvcEvent $e)
        {
            _dump('ERROR IN DISPATCH: ' . $e->getError());
        }, 100);
        
        $serverConfig = new Config(require __DIR__ . '/config/server.config.php');
        $serviceManager->setService('ServerConfig', $serverConfig);
        
        $logger = $this->_initLogger($serverConfig);
        $serviceManager->setService('Logger', $logger);
    }


    protected function _initLogger (\Zend\Config\Config $serverConfig)
    {
        $loggerConfig = $serverConfig->logger;
        $writerConfigs = $loggerConfig->writers->toArray();
        
        $logger = new \Zend\Log\Logger();
        if (count($writerConfigs)) {
            $priority = 1;
            foreach ($writerConfigs as $writerConfig) {
                $logger->addWriter($writerConfig['name'], $priority ++, $writerConfig['options']);
            }
        }
        
        //\Zend\Log\Logger::registerErrorHandler($logger);
        //\Zend\Log\Logger::registerExceptionHandler($logger);
        

        return $logger;
    }
}
