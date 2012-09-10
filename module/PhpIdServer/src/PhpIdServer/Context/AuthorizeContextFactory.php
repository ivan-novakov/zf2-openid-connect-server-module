<?php
namespace PhpIdServer\Context;
use PhpIdServer\OpenIdConnect;


class AuthorizeContextFactory implements \Zend\ServiceManager\FactoryInterface
{


    public function createService (\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $sessionContainer = new \Zend\Session\Container('authorize');
        $contextStorage = new Storage\SessionStorage($sessionContainer);
        $serviceLocator->setService('ContextStorage', $contextStorage);
        
        $context = $contextStorage->load();
        if (! $context) {
            $context = new AuthorizeContext();
            $context->setRequest(OpenIdConnect\RequestFactory::factory(new \Zend\Http\PhpEnvironment\Request()));
        }
        
        $serviceLocator->setService('AuthorizeContext', $context);
        
        return $context;
    }
}