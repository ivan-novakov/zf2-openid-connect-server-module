<?php
return array(
    
    'router' => array(
        'routes' => array(
            'oic' => array(
                'type' => 'Literal',
                'may_terminate' => true,
                'options' => array(
                    
                    'route' => '/oic',
                    'defaults' => array(
                        'controller' => 'InoOicServer\Mvc\Controller\OicIndexController',
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
                                'controller' => 'InoOicServer\Mvc\Controller\AuthorizeController',
                                'action' => 'authorize'
                            )
                        )
                    ),
                    
                    'authorize-response-endpoint' => array(
                        'type' => 'Literal',
                        'may_terminate' => true,
                        'options' => array(
                            'route' => '/authorize-response',
                            'defaults' => array(
                                'controller' => 'InoOicServer\Mvc\Controller\AuthorizeController',
                                'action' => 'response'
                            )
                        )
                    ),
                    
                    'token-endpoint' => array(
                        'type' => 'Literal',
                        'may_terminate' => true,
                        'options' => array(
                            'route' => '/token',
                            'defaults' => array(
                                'controller' => 'InoOicServer\Mvc\Controller\TokenController',
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
                                'controller' => 'InoOicServer\Mvc\Controller\UserinfoController',
                                'action' => 'index'
                            )
                        )
                    ),
                    
                    'authentication' => array(
                        'type' => 'segment',
                        'may_terminate' => true,
                        'options' => array(
                            'route' => '/authn/:controller/authenticate',
                            'constraints' => array(
                                'controller' => '[a-z][a-z0-9_-]+'
                            ),
                            'defaults' => array(
                                'action' => 'authenticate'
                            )
                        )
                    )
                )
            )
        )
    ),
    
    'controllers' => array(
        'aliases' => array(
            'dummy' => 'InoOicServer\Oic\Authentication\Controller\DummyController',
            'basic' => 'InoOicServer\Oic\Authentication\Controller\BasicAuthController'
        )
    ),
    
    'oic_server' => array(
        'user_authentication_manager' => array(
            'method' => 'dummy',
            'auth_route' => 'oic/authentication',
            'return_route' => 'oic/authorize-response-endpoint'
        ),
        
        'client_mapper' => array(
            'file' => __DIR__ . '/data/clients.php'
        ),
        
        'auth_session_service' => array(
            'salt' => 'auth session salt',
            'age' => 1800
        ),
        
        'session_service' => array(
            'salt' => 'session salt',
            'age' => 3600
        ),
        
        'auth_code_service' => array(
            'salt' => 'auth code salt',
            'age' => 7200
        )
    )
);
