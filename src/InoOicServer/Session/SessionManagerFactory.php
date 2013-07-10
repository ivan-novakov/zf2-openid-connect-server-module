<?php

namespace InoOicServer\Session;

use InoOicServer\Session\Hash;
use InoOicServer\Session\IdGenerator;
use InoOicServer\User\Serializer\Serializer;
use InoOicServer\Session\Storage\Dummy;


class SessionManagerFactory implements \Zend\ServiceManager\FactoryInterface
{


    public function createService (\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $sessionManager = new SessionManager();
        
        $storage = $serviceLocator->get('InoOicServer\SessionStorage');
        $serializer = $serviceLocator->get('InoOicServer\UserSerializer');
        $idGenerator = $serviceLocator->get('InoOicServer\SessionIdGenerator');
        $hashGenerator = $serviceLocator->get('InoOicServer\TokenGenerator');
        
        $sessionManager->setStorage($storage);
        $sessionManager->setSessionIdGenerator($idGenerator);
        $sessionManager->setTokenGenerator($hashGenerator);
        $sessionManager->setUserSerializer($serializer);
        
        return $sessionManager;
    }
}