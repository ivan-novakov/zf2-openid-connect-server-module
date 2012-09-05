<?php
return array(
    
    'controllers' => array(
        'invokables' => array(
            'PhpIdServer\Controller\Index' => 'PhpIdServer\Controller\IndexController', 
            'PhpIdServer\Controller\Hello' => 'PhpIdServer\Controller\HelloController',
            'PhpIdServer\Controller\Authorize' => 'PhpIdServer\Controller\AuthorizeController',
        )
    ), 
    
    'router' => array(
        'routes' => array(
            'php-id-server' => array(
                'type' => 'Literal', 
                'options' => array(
                    
                    'route' => '/mymod', 
                    'defaults' => array(
                        '__NAMESPACE__' => 'PhpIdServer\Controller', 
                        'controller' => 'Index', 
                        'action' => 'index'
                    )
                ), 
                'may_terminate' => true, 
                'child_routes' => array(
                    
                    'default' => array(
                        'type' => 'Segment', 
                        'options' => array(
                            'route' => '/[:controller[/:action]]', 
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*', 
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ), 
                            'defaults' => array()
                        )
                    )
                )
            )
        )
    ), 
    /*
    'controller' => array(
        'classes' => array(
            'php-id-server-Index' => 'PhpIdServer\Controller\IndexController', 
            'PhpIdServer-Hello' => 'PhpIdServer\Controller\HelloController'
        )
    ), 
    */
    'view_manager' => array(
        'template_path_stack' => array(
            'php-id-server' => __DIR__ . '/../view'
        )
    )
);
