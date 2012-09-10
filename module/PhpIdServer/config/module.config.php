<?php
return array(
    
    'controllers' => array(
        'invokables' => array(
            'PhpIdServer\Controller\Index' => 'PhpIdServer\Controller\IndexController', 
            'PhpIdServer\Controller\Authorize' => 'PhpIdServer\Controller\AuthorizeController', 
            'PhpIdServer\Controller\Token' => 'PhpIdServer\Controller\TokenController', 
            'PhpIdServer\Controller\Userinfo' => 'PhpIdServer\Controller\UserinfoController', 
            'PhpIdServer\Controller\Error' => 'PhpIdServer\Controller\ErrorController', 
            
            'PhpIdServer\Controller\Authenticate' => 'PhpIdServer\Controller\AuthenticateController', 
            
            'PhpIdServer\Authentication\Controller\Dummy' => 'PhpIdServer\Authentication\Controller\DummyController'
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
                        // '__NAMESPACE__' => 'PhpIdServer\Controller', 
                        'controller' => 'PhpIdServer\Controller\Index', 
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
                                'controller' => 'PhpIdServer\Controller\Authorize', 
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
                                'controller' => 'PhpIdServer\Controller\Token', 
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
                                'controller' => 'PhpIdServer\Controller\Userinfo', 
                                'action' => 'index'
                            )
                        )
                    ), 
                    
                    'authentication-endpoint' => array(
                        'type' => 'Literal', 
                        'may_terminate' => true, 
                        'options' => array(
                            'route' => '/auth', 
                            'defaults' => array(
                                'controller' => 'PhpIdServer\Controller\Authenticate', 
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
                                'controller' => 'PhpIdServer\Authentication\Controller\Dummy',
                                'action' => 'authenticate', 
                                'options' => array(
                                    'identity' => array(
                                        'uid' => 'testuser@company.org'
                                    )
                                )
                            )
                        )
                    )
                    
                    
                )
            )
        )
    ), 
    
    // OBSOLETE ?
    'controller' => array(
        'classes' => array(
            'php-id-server-Index' => 'PhpIdServer\Controller\IndexController', 
            'PhpIdServer-Authorize' => 'PhpIdServer\Controller\AuthorizeController'
        )
    ), 
    
    'view_manager' => array(
        'template_path_stack' => array(
            'php-id-server' => __DIR__ . '/../view'
        )
    )
);
