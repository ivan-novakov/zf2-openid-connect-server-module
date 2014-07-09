<?php
return array(
    
    'router' => array(
        'routes' => array(
            
            'root' => array(
                'type' => 'Literal',
                'may_terminate' => true,
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'InoOicServer\IndexController',
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
                                'controller' => 'InoOicServer\DiscoveryController',
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
                        'controller' => 'InoOicServer\IndexController',
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
                                'controller' => 'InoOicServer\AuthorizeController',
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
                                'controller' => 'InoOicServer\AuthorizeController',
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
                                'controller' => 'InoOicServer\TokenController',
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
                                'controller' => 'InoOicServer\UserinfoController',
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
    
    'oic_server' => array(
        
        'logger' => array(
            'writers' => array(
                'stream' => array(
                    'name' => 'stream',
                    'options' => array(
                        'stream' => '/data/var/log/phpid-server.log'
                    ),
                    'filters' => array(
                        'priority' => 7
                    ),
                    'formatter' => array(
                        'format' => '%timestamp% %priorityName% (%priority%): %message% %extra%',
                        'dateTimeFormat' => 'Y-m-d H:i:s'
                    )
                )
            )
        ),
        
        'oic_server_info' => array(
            'base_uri' => 'https://oic.server.org/authn',
            'service_documentation' => 'https://github.com/ivan-novakov/zf2-openid-connect-server-module'
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
                'class' => 'InoOicServer\Authentication\Controller\DummyController',
                'options' => array(
                    'label' => 'dummy',
                    'identity' => array(
                        'id' => 'vomacka@example.cz',
                        'name' => 'Franta Vomacka',
                        'given_name' => 'Franta',
                        'family_name' => 'Vomacka',
                        'nickname' => 'killer_vom',
                        'email' => 'franta.vomacka@example.cz'
                    )
                )
            ),
            
            'basic-auth' => array(
                'class' => 'InoOicServer\Authentication\Controller\BasicAuthController',
                'options' => array(
                    'file' => 'data/auth/users.php'
                )
            ),
            
            'shibboleth' => array(
                'class' => 'InoOicServer\Authentication\Controller\ShibbolethController',
                'options' => array(
                    'system_attributes_map' => array(
                        'Shib-Session-ID' => 'session_id'
                    ),
                    'user_attributes_map' => array(
                        'REMOTE_USER' => 'id',
                        'cn' => 'name',
                        'givenName' => 'given_name',
                        'sn' => 'family_name',
                        'mail' => 'email'
                    ),
                    'attribute_filter' => array(
                        'REMOTE_USER' => array(
                            'name' => 'remote_user',
                            'required' => true
                        ),
                        'cn' => array(
                            'required' => true
                        ),
                        'mail' => array(
                            'required' => false
                        ),
                        'givenName' => array(
                            'required' => false
                        ),
                        'sn' => array(
                            'required' => false
                        )
                    )
                )
            )
        ),
        
        'context_authorize' => array(
            'timeout' => 1800
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
        
        'oic_session_manager' => array(
            'session_expire_interval' => 'PT1H',
            'authorization_code_expire_interval' => 'PT5M',
            'access_token_expire_interval' => 'PT12H',
            'refresh_token_expire_interval' => 'PT24H'
        ),
        
        'session_id_generator' => array(
            'class' => '\InoOicServer\Session\IdGenerator\Simple',
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
            'user_class' => '\InoOicServer\User\User'
        ),
        
        'data_connectors' => array(
            /*
            'test' => array(
                'class' => '\InoOicServer\User\DataConnector\Dummy',
                'options' => array()
            )
            */
        ),
        
        'user_validators' => array(
            /*
            'dummy' => array(
                'class' => '\InoOicServer\User\Validator\Dummy',
                'options' => array(
                    'valid' => true,
                    'redirect_uri' => 'http://registration.example.org/'
                )
            )
            */
        ),
        
        'user_info_mapper' => array(
            'class' => '\InoOicServer\User\UserInfo\Mapper\ToArray'
        ),
        
        'client_authentication_manager' => array(
            'methods' => array(
                'dummy' => array(
                    'class' => '\InoOicServer\Client\Authentication\Method\Dummy',
                    'options' => array(
                        'success' => true
                    )
                ),
                'client_secret_basic' => array(
                    'class' => '\InoOicServer\Client\Authentication\Method\SecretBasic'
                ),
                'client_secret_post' => array(
                    'class' => '\InoOicServer\Client\Authentication\Method\SecretPost'
                )
            )
        ),
        
        'filter_invokables' => array(
            'shibboleth_serialized_value' => 'InoOicServer\Util\Filter\ShibbolethSerializedValue'
        ),
        
        'ua_session_manager' => array(
            'config' => array(
                'class' => 'Zend\Session\Config\SessionConfig',
                'options' => array(
                    'name' => 'oicserver',
                    'cookie_secure' => true,
                    'cookie_httponly' => true,
                    'remember_me_seconds' => 3600
                )
            )
        )
    )
);
