<?php

namespace InoOicServer\ServiceManager;

use InoOicServer\Util\UrlHelper;
use Zend\ServiceManager\Config;
use InoOicServer\Oic\Authorize\Http\HttpService;
use InoOicServer\Oic\Authorize\AuthorizeService;
use InoOicServer\Oic\Client\ClientService;
use InoOicServer\Oic\Client\Mapper\PhpArrayInFile;
use InoOicServer\Oic\Authorize\Context\ContextService;
use InoOicServer\Oic\Authorize\Context\SessionStorage;
use InoOicServer\Oic\AuthSession\AuthSessionService;
use InoOicServer\Oic\Session\SessionService;
use InoOicServer\Oic\AuthCode\AuthCodeService;
use InoOicServer\Oic\AuthCode;
use InoOicServer\Oic\Session;
use InoOicServer\Oic\AuthSession;
use InoOicServer\Oic\User;


class ServiceManagerConfig extends Config
{


    public function getFactories()
    {
        return array(
            
            'Zend\Session\Container' => function ($sm)
            {
                $container = new \Zend\Session\Container();
                
                return $container;
            },
            
            'InoOicServer\Util\UrlHelper' => function ($sm)
            {
                $urlHelper = new UrlHelper($sm->get('Router'));
                
                return $urlHelper;
            },
            
            'InoOicServer\Oic\User\Authentication\Manager' => function ($sm)
            {
                $config = $sm->get('Config');
                if (! isset($config['oic_server']['user_authentication_manager']) || ! is_array($config['oic_server']['user_authentication_manager'])) {
                    throw new Exception\MissingConfigException('oic_server/user_authentication_manager');
                }
                
                $options = $config['oic_server']['user_authentication_manager'];
                $urlHelper = $sm->get('InoOicServer\Util\UrlHelper');
                
                $manager = new User\Authentication\Manager($options, $urlHelper);
            },
            
            'InoOicServer\Oic\Authorize\Http\HttpService' => function ($sm)
            {
                $httpService = new HttpService();
                
                return $httpService;
            },
            
            'InoOicServer\Oic\Client\Mapper' => function ($sm)
            {
                $config = $sm->get('Config');
                if (! isset($config['oic_server']['client_mapper']) || ! is_array($config['oic_server']['client_mapper'])) {
                    throw new Exception\MissingConfigException('oic_server/client_mapper');
                }
                
                $clientMapper = new PhpArrayInFile($config['oic_server']['client_mapper']);
                
                return $clientMapper;
            },
            
            'InoOicServer\Oic\Client\ClientService' => function ($sm)
            {
                $clientMapper = $sm->get('InoOicServer\Oic\Client\Mapper');
                $clientService = new ClientService($clientMapper);
                
                return $clientService;
            },
            
            'InoOicServer\Oic\Authorize\Context\Storage' => function ($sm)
            {
                $sessionContainer = $sm->get('Zend\Session\Container');
                $storage = new SessionStorage($sessionContainer);
                
                return $storage;
            },
            
            'InoOicServer\Oic\Authorize\Context\ContextService' => function ($sm)
            {
                $storage = $sm->get('InoOicServer\Oic\Authorize\Context\Storage');
                $contextService = new ContextService($storage);
                
                return $contextService;
            },
            
            'InoOicServer\Oic\AuthSession\Mapper' => function ($sm)
            {
                $mapper = new AuthSession\Mapper\DbMapper();
                
                return $mapper;
            },
            
            'InoOicServer\Oic\AuthSession\AuthSessionService' => function ($sm)
            {
                $config = $sm->get('Config');
                if (! isset($config['oic_server']['auth_session_service']) || ! is_array($config['oic_server']['auth_session_service'])) {
                    throw new Exception\MissingConfigException('oic_server/auth_session_service');
                }
                
                $authSessionMapper = $sm->get('InoOicServer\Oic\AuthSession\Mapper');
                $options = $config['oic_server']['auth_session_service'];
                $authSessionService = new AuthSessionService($authSessionMapper, $options);
                
                return $authSessionService;
            },
            
            'InoOicServer\Oic\Session\Mapper' => function ($sm)
            {
                $mapper = new Session\Mapper\DbMapper();
                
                return $mapper;
            },
            
            'InoOicServer\Oic\Session\SessionService' => function ($sm)
            {
                $config = $sm->get('Config');
                if (! isset($config['oic_server']['session_service']) || ! is_array($config['oic_server']['session_service'])) {
                    throw new Exception\MissingConfigException('oic_server/session_service');
                }
                
                $options = $config['oic_server']['session_service'];
                $sessionMapper = $sm->get('InoOicServer\Oic\Session\Mapper');
                $sessionService = new SessionService($sessionMapper, $options);
                
                return $sessionService;
            },
            
            'InoOicServer\Oic\AuthCode\Mapper' => function ($sm)
            {
                $mapper = new AuthCode\Mapper\DbMapper();
                
                return $mapper;
            },
            
            'InoOicServer\Oic\AuthCode\AuthCodeService' => function ($sm)
            {
                $config = $sm->get('Config');
                if (! isset($config['oic_server']['auth_code_service']) || ! is_array($config['oic_server']['auth_code_service'])) {
                    throw new Exception\MissingConfigException('oic_server/auth_code_service');
                }
                
                $options = $config['oic_server']['auth_code_service'];
                $authCodeMapper = $sm->get('InoOicServer\Oic\AuthCode\Mapper');
                $authCodeService = new AuthCodeService($authCodeMapper, $options);
                
                return $authCodeService;
            },
            
            'InoOicServer\Oic\Authorize\AuthorizeService' => function ($sm)
            {
                $clientService = $sm->get('InoOicServer\Oic\Client\ClientService');
                $contextService = $sm->get('InoOicServer\Oic\Authorize\Context\ContextService');
                $authSessionService = $sm->get('InoOicServer\Oic\AuthSession\AuthSessionService');
                $sessionService = $sm->get('InoOicServer\Oic\Session\SessionService');
                $authCodeService = $sm->get('InoOicServer\Oic\AuthCode\AuthCodeService');
                
                $authorizeService = new AuthorizeService($clientService, $contextService, $authSessionService, $sessionService, $authCodeService);
                
                return $authorizeService;
            }
        );
    }
}