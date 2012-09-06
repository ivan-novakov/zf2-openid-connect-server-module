<?php
return array(
    
    'controllers' => array(
        'invokables' => array(
            'PhpIdServer\Controller\Index' => 'PhpIdServer\Controller\IndexController', 
            'PhpIdServer\Controller\Authorize' => 'PhpIdServer\Controller\AuthorizeController', 
            'PhpIdServer\Controller\Token' => 'PhpIdServer\Controller\TokenController', 
            'PhpIdServer\Controller\Userinfo' => 'PhpIdServer\Controller\UserinfoController', 
            'PhpIdServer\Controller\Error' => 'PhpIdServer\Controller\ErrorController', 
            'PhpIdServer\Controller\Authenticate' => 'PhpIdServer\Controller\AuthenticateController'
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
                        '__NAMESPACE__' => 'PhpIdServer\Controller', 
                        'controller' => 'index', 
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
