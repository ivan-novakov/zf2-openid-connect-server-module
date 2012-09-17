<?php

namespace PhpIdServer\Session;

use PhpIdServer\Session\Hash;
use PhpIdServer\Session\IdGenerator;
use PhpIdServer\User\Serializer\Serializer;
use PhpIdServer\Session\Storage\Dummy;


class SessionManagerFactory implements \Zend\ServiceManager\FactoryInterface
{


    public function createService (\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $sessionManager = new SessionManager();
        
        $storage = $serviceLocator->get('SessionStorage');
        $serializer = $serviceLocator->get('UserSerializer');
        $idGenerator = $serviceLocator->get('SessionIdGenerator');
        $hashGenerator = $serviceLocator->get('TokenGenerator');
        
        $sessionManager->setStorage($storage);
        $sessionManager->setSessionIdGenerator($idGenerator);
        $sessionManager->setTokenGenerator($hashGenerator);
        $sessionManager->setUserSerializer($serializer);
        
        return $sessionManager;
    }
}