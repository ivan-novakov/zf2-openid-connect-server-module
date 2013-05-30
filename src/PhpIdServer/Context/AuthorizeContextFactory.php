<?php

namespace PhpIdServer\Context;

use PhpIdServer\OpenIdConnect;


class AuthorizeContextFactory implements \Zend\ServiceManager\FactoryInterface
{


    public function createService (\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $contextStorage = $serviceLocator->get('PhpIdServer\ContextStorage');
        
        $context = $contextStorage->load();
        if (! $context) {
            $context = new AuthorizeContext();
            $context->setRequest(OpenIdConnect\Request\Authorize\RequestFactory::factory(new \Zend\Http\PhpEnvironment\Request()));
        }
        
        return $context;
    }
}