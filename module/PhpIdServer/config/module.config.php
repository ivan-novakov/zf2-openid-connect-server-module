<?php

use Zend\Log\Logger;

use PhpIdServer\User\User;

return array(
    
    'controllers' => array(
        'invokables' => array(
            'controller-index' => 'PhpIdServer\Controller\IndexController', 
            'controller-authorize' => 'PhpIdServer\Controller\AuthorizeController', 
            'controller-token' => 'PhpIdServer\Controller\TokenController', 
            'controller-userinfo' => 'PhpIdServer\Controller\UserinfoController', 
            //'PhpIdServer\Controller\Error' => 'PhpIdServer\Controller\ErrorController', 
            'controller-auth-dummy' => 'PhpIdServer\Authentication\Controller\DummyController'
        )
    ), 
    
    'router' => array(
        'routes' => array(
            'php-id-server' => array(
                
                'type' => 'Literal', 
                'may_terminate' => true, 
                'options' => array(
                    
                    'route' => '/oic', 
                    'defaults' => array(
                        'controller' => 'controller-index', 
                        'action' => 'index'
                    )
                ), 
                
                'child_routes' => array(
                    
                    'authorize-endpoint' => array(
                        'type' => 'Literal', 
                        'may_terminate' => true, 
                        'options' => array(
                            'route' => '/authorize', 
                            'defaults' => array(
                                'controller' => 'controller-authorize', 
                                'action' => 'index'
                            )
                        )
                    ), 
                    
                    'token-endpoint' => array(
                        'type' => 'Literal', 
                        'may_terminate' => true, 
                        'options' => array(
                            'route' => '/token', 
                            'defaults' => array(
                                'controller' => 'controller-token', 
                                'action' => 'index'
                            )
                        )
                    ), 
                    
                    'userinfo-endpoint' => array(
                        'type' => 'Literal', 
                        'may_terminate' => true, 
                        'options' => array(
                            'route' => '/userinfo', 
                            'defaults' => array(
                                'controller' => 'controller-userinfo', 
                                'action' => 'index'
                            )
                        )
                    ), 
                    
                    //'authentication-endpoint-shibboleth' => array(), 
                    

                    'authentication-endpoint-dummy' => array(
                        'type' => 'Literal', 
                        'may_terminate' => true, 
                        'options' => array(
                            'route' => '/authenticate/dummy', 
                            'defaults' => array(
                                'controller' => 'controller-auth-dummy', 
                                'action' => 'authenticate', 
                                'options' => array(
                                    'label' => 'dummy', 
                                    'identity' => array(
                                        User::FIELD_ID => 'vomacka@example.cz', 
                                        User::FIELD_NAME => 'Franta Vomacka', 
                                        User::FIELD_GIVEN_NAME => 'Franta', 
                                        User::FIELD_FAMILY_NAME => 'Vomacka', 
                                        User::FIELD_NICKNAME => 'killer_vom', 
                                        User::FIELD_EMAIL => 'franta.vomacka@example.cz'
                                    )
                                )
                            )
                        )
                    ), 
                    
                    'authentication-endpoint-static' => array(
                        'type' => 'Literal', 
                        'may_terminate' => true, 
                        'options' => array(
                            'route' => '/authenticate/static', 
                            'defaults' => array(
                                'controller' => 'controller-auth-static', 
                                'action' => 'authenticate', 
                                'options' => array()
                            )
                        )
                    )
                )
            )
        )
    ), 
    
    'view_manager' => array(
        'template_path_stack' => array(
            'php-id-server' => __DIR__ . '/../view'
        ), 
        
        'display_exceptions' => true, 
        'exception_template' => 'error/index', 
        
        'display_not_found_reason' => true, 
        'not_found_template' => 'error/404', 
        
        'template_map' => array(
            'error/index' => __DIR__ . '/../view/error/error.phtml'
        )
    ), 
    
    'service_manager' => array(
        'factories' => array(
            'AuthorizeContext' => 'PhpIdServer\Context\AuthorizeContextFactory', 
            'ContextStorage' => 'PhpIdServer\Context\Storage\StorageFactory', 
            'SessionManager' => 'PhpIdServer\Session\SessionManagerFactory', 
            'SessionStorage' => 'PhpIdServer\Session\Storage\StorageFactory', 
            'ClientRegistryStorage' => 'PhpIdServer\Client\Registry\StorageFactory', 
            'ClientRegistry' => 'PhpIdServer\Client\RegistryFactory'
        ), 
        'invokables' => array(
            'TokenGenerator' => 'PhpIdServer\Session\Hash\Generator\Simple'
        )
    ), 
    
    'logger' => array(
        'writers' => array(
            array(
                'name' => 'stream', 
                'options' => array(
                    'stream' => '/data/var/log/devel/phpid-server/phpid-server.log'
                ), 
                'filters' => array(
                    'priority' => Logger::DEBUG
                )
            )
        )
    ), 
    
    'client_registry_storage' => array(
        'type' => 'SingleJsonFileStorage', 
        'options' => array(
            'json_file' => 'data/client/metadata.json'
        )
    ), 
    
    'authentication' => array(
        'handler_endpoint_route' => 'php-id-server/authentication-endpoint-dummy'
    ), 
    
    'context_storage' => array(
        'type' => 'Session', 
        'options' => array(
            'session_container_name' => 'authorize'
        )
    ), 
    
    'session_storage' => array(
        'type' => 'MysqlLite', 
        'options' => array(
            
            'session_table' => 'session', 
            'authorization_code_table' => 'authorization_code', 
            'access_token_table' => 'access_token', 
            'refresh_token_table' => 'refresh_token', 
            
            'adapter' => array(
                'driver' => 'Pdo_Mysql', 
                'host' => 'localhost', 
                'username' => 'phpid_admin', 
                'password' => 'phpid admin heslo', 
                'database' => 'phpidserver'
            )
        )
    ), 
    
    'session_id_generator' => array(
        'class' => '\PhpIdServer\Session\IdGenerator\Simple', 
        'options' => array(
            'secret_salt' => 'tajna sul'
        )
    ), 
    
    'user_serializer' => array(
        'adapter' => array(
            'name' => 'PhpSerialize', 
            'options' => array()
        )
    )
);
