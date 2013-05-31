<?php
use Zend\Log\Logger;
use PhpIdServer\User\User;

return array(
    
    'router' => array(
        'routes' => array(
            
            'root' => array(
                'type' => 'Literal',
                'may_terminate' => true,
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'PhpIdServer\IndexController',
                        'action' => 'index'
                    )
                )
            ),
            
            /*
             * Discovery with "well-known URL" implementation
             * Specs:
             *   - http://openid.net/specs/openid-connect-discovery-1_0.html
             *   - http://tools.ietf.org/html/rfc5785
             */
            'well-known' => array(
                'type' => 'Literal',
                'may_terminate' => false,
                'options' => array(
                    'route' => '/.well-known'
                ),
                'child_routes' => array(
                    'openid-configuration' => array(
                        'type' => 'Literal',
                        'may_terminate' => true,
                        'options' => array(
                            'route' => '/openid-configuration',
                            'defaults' => array(
                                'controller' => 'PhpIdServer\DiscoveryController',
                                'action' => 'index'
                            )
                        )
                    )
                )
            ),
            
            'php-id-server' => array(
                
                'type' => 'Literal',
                'may_terminate' => true,
                'options' => array(
                    
                    'route' => '/oic',
                    'defaults' => array(
                        'controller' => 'PhpIdServer\IndexController',
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
                                'controller' => 'PhpIdServer\AuthorizeController',
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
                                'controller' => 'PhpIdServer\TokenController',
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
                                'controller' => 'PhpIdServer\UserinfoController',
                                'action' => 'index'
                            )
                        )
                    ),
                    
                    'authentication' => array(
                        'type' => 'segment',
                        'may_terminate' => true,
                        'options' => array(
                            'route' => '/authn/:controller[/:action]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]+'
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
    
    'logger' => array(
        'writers' => array(
            'stream' => array(
                'name' => 'stream',
                'options' => array(
                    'stream' => '/data/var/log/phpid-server.log'
                ),
                'filters' => array(
                    'priority' => Logger::DEBUG
                ),
                'formatter' => array(
                    'format' => '%timestamp% %priorityName% (%priority%): %message% %extra%',
                    'dateTimeFormat' => 'Y-m-d H:i:s'
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
        'base_route' => 'php-id-server/authentication',
        'default_authentication_handler' => 'dummy'
    ),
    
    'authentication_handlers' => array(
        
        'dummy' => array(
            'class' => 'PhpIdServer\Authentication\Controller\DummyController',
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
        ),
        
        'basic-auth' => array(
            'class' => 'PhpIdServer\Authentication\Controller\BasicAuthController',
            'options' => array(
                'file' => 'data/auth/users.php'
            )
        ),
        
        'shibboleth' => array(
            'class' => 'PhpIdServer\Authentication\Controller\ShibbolethController',
            'options' => array(
                'system_attributes_map' => array(
                    'Shib-Session-ID' => 'session_id'
                ),
                'user_attributes_map' => array(
                    'REMOTE_USER' => User::FIELD_ID,
                    'cn' => User::FIELD_NAME,
                    'givenName' => User::FIELD_GIVEN_NAME,
                    'sn' => User::FIELD_FAMILY_NAME,
                    'mail' => User::FIELD_EMAIL
                ),
                'attribute_filter' => array(
                    'REMOTE_USER' => array(
                        'name' => 'remote_user',
                        'required' => true
                    )
                )
            )
        )
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
                'username' => 'phpidadmin',
                'password' => 'phpidadminpassword',
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
    ),
    
    'user_factory' => array(
        'user_class' => '\PhpIdServer\User\User'
    ),
    
    'data_connectors' => array(
        'test' => array(
            'class' => '\PhpIdServer\User\DataConnector\Dummy',
            'options' => array()
        )
    ),
    
    'user_info_mapper' => array(
        'class' => '\PhpIdServer\User\UserInfo\Mapper\ToArray'
    ),
    
    'client_authentication_manager' => array(
        'methods' => array(
            'dummy' => array(
                'class' => '\PhpIdServer\Client\Authentication\Method\Dummy',
                'options' => array(
                    'success' => true
                )
            ),
            'secret' => array(
                'class' => '\PhpIdServer\Client\Authentication\Method\SharedSecret',
                'options' => array()
            ),
            'pki' => array(
                'class' => '\PhpIdServer\Client\Authentication\Method\Pki',
                'options' => array()
            )
        )
    ),
    
    'filter_invokables' => array(
        'shibboleth_serialized_value' => 'PhpIdServer\Util\Filter\ShibbolethSerializedValue'
    )
);
