<?php

namespace InoOicServer\ServiceManager;

use InoOicServer\Session\SessionManager;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config;
use InoOicServer\General\Exception as GeneralException;
use InoOicServer\User\DataConnector\DataConnectorFactory;
use InoOicServer\Util\String;
use InoOicServer\User;
use InoOicServer\Authentication;
use InoOicServer\OpenIdConnect\Dispatcher;
use InoOicServer\OpenIdConnect\Response;
use InoOicServer\OpenIdConnect\Request;
use InoOicServer\Client;
use Zend\InputFilter\Factory;
use Zend\Filter\FilterChain;
use InoOicServer\Util\ErrorHandler;
use InoOicServer\OpenIdConnect\Request\Authorize\Simple;
use InoOicServer\Context\AuthorizeContextManager;


class ServiceManagerConfig extends Config
{

    const CONFIG_SESSION_MANAGER = 'oic_session_manager';


    public function getInvokables()
    {
        return array(
            'InoOicServer\TokenGenerator' => 'InoOicServer\Session\Hash\Generator\Simple',
            'InoOicServer\AuthorizeRequestFactory' => 'InoOicServer\OpenIdConnect\Request\Authorize\RequestFactory',
            'InoOicServer\AuthorizeContextFactory' => 'InoOicServer\Context\AuthorizeContextFactory'
        );
    }


    public function getFactories()
    {
        $smc = $this;
        return array(
            'InoOicServer\ContextStorage' => 'InoOicServer\Context\Storage\StorageFactory',
            // 'InoOicServer\SessionManager' => 'InoOicServer\Session\SessionManagerFactory',
            'InoOicServer\SessionStorage' => 'InoOicServer\Session\Storage\StorageFactory',
            'InoOicServer\ClientRegistryStorage' => 'InoOicServer\Client\Registry\StorageFactory',
            'InoOicServer\ClientRegistry' => 'InoOicServer\Client\RegistryFactory',
            
            /*
             * Main logger object
             */
            'InoOicServer\Logger' => function (ServiceManager $sm)
            {
                $config = $sm->get('Config');
                $loggerConfig = $config['logger'];
                if (! isset($loggerConfig['writers'])) {
                    throw new Exception\ConfigNotFoundException('logger/writers');
                }
                
                $logger = new \Zend\Log\Logger();
                
                if (count($loggerConfig['writers'])) {
                    
                    $priority = 1;
                    foreach ($loggerConfig['writers'] as $writerConfig) {
                        
                        $writer = $logger->writerPlugin($writerConfig['name'], $writerConfig['options']);
                        
                        if (isset($writerConfig['filters']) && is_array($writerConfig['filters'])) {
                            foreach ($writerConfig['filters'] as $filterName => $filterValue) {
                                $filterClass = '\Zend\Log\Filter\\' . String::underscoreToCamelCase($filterName);
                                $filter = new $filterClass($filterValue);
                                $writer->addFilter($filter);
                            }
                        }
                        
                        if (isset($writerConfig['formatter']) && is_array($writerConfig['formatter']) &&
                             isset($writerConfig['formatter'])) {
                            $formatterConfig = $writerConfig['formatter'];
                            if (isset($formatterConfig['format'])) {
                                $formatter = new \Zend\Log\Formatter\Simple($formatterConfig['format']);
                                if (isset($formatterConfig['dateTimeFormat'])) {
                                    $formatter->setDateTimeFormat($formatterConfig['dateTimeFormat']);
                                }
                                
                                $writer->setFormatter($formatter);
                            }
                        }
                        
                        $logger->addWriter($writer, $priority ++);
                    }
                }
                
                return $logger;
            },
            
            'InoOicServer\ErrorHandler' => function (ServiceManager $sm)
            {
                return new ErrorHandler($sm->get('InoOicServer\Logger'));
            },
            
            /*
             * User/Serializer
             */
            'InoOicServer\UserSerializer' => function (ServiceManager $sm)
            {
                $config = $sm->get('Config');
                if (! isset($config['user_serializer'])) {
                    throw new Exception\ConfigNotFoundException('user_serializer');
                }
                
                return new User\Serializer\Serializer($config['user_serializer']);
            }, 
            
            /*
             * User/UserFactory
             */
            'InoOicServer\UserFactory' => function (ServiceManager $sm)
            {
                $config = $sm->get('Config');
                if (! isset($config['user_factory'])) {
                    throw new Exception\ConfigNotFoundException('user_factory');
                }
                
                return new User\UserFactory($config['user_factory']);
            }, 
            
            /*
             * User/DataConnector/DataConnectorFactory
             */
            'InoOicServer\UserDataConnectorFactory' => function (ServiceManager $sm)
            {
                return new DataConnectorFactory();
            }, 
            
            /*
             * The default user data connector.
             * User/DataConnector/Chain
             */
            'InoOicServer\UserDataConnector' => function (ServiceManager $sm)
            {
                $config = $sm->get('Config');
                if (! isset($config['data_connectors'])) {
                    throw new Exception\ConfigNotFoundException('data_connectors');
                }
                
                $dataConnectorConfigs = $config['data_connectors'];
                $factory = $sm->get('InoOicServer\UserDataConnectorFactory');
                $chain = $factory->createDataConnector(
                    array(
                        'class' => '\InoOicServer\User\DataConnector\Chain'
                    ));
                foreach ($dataConnectorConfigs as $dataConnectorConfig) {
                    $chain->addDataConnector($factory->createDataConnector($dataConnectorConfig));
                }
                
                return $chain;
            }, 
            
            /*
             * User/UserInfo/Mapper/MapperInterface
             */
            'InoOicServer\UserInfoMapper' => function (ServiceManager $sm)
            {
                $config = $sm->get('Config');
                if (! isset($config['user_info_mapper'])) {
                    throw new Exception\ConfigNotFoundException('user_info_mapper');
                }
                
                $mapperConfig = $config['user_info_mapper'];
                if (! isset($mapperConfig['class'])) {
                    throw new Exception\ConfigNotFoundException('user_info_mapper / class');
                }
                
                $className = $mapperConfig['class'];
                if (! class_exists($className)) {
                    throw new GeneralException\InvalidClassException(sprintf("Non-existent class '%s'", $className));
                }
                
                return new $className();
            },
            
            'InoOicServer\SessionManager' => function (ServiceManager $sm) use($smc)
            {
                $config = $sm->get('Config');
                if (! isset($config[$smc::CONFIG_SESSION_MANAGER]) || ! is_array(
                    $config[$smc::CONFIG_SESSION_MANAGER])) {
                    throw new Exception\ConfigNotFoundException($smc::CONFIG_SESSION_MANAGER);
                }
                
                $sessionManager = new SessionManager($config[$smc::CONFIG_SESSION_MANAGER]);
                
                $sessionManager->setStorage($sm->get('InoOicServer\SessionStorage'));
                $sessionManager->setSessionIdGenerator($sm->get('InoOicServer\SessionIdGenerator'));
                $sessionManager->setTokenGenerator($sm->get('InoOicServer\TokenGenerator'));
                $sessionManager->setUserSerializer($sm->get('InoOicServer\UserSerializer'));
                
                return $sessionManager;
            },
            
            /*
             * Session/IdGenerator
             */
            'InoOicServer\SessionIdGenerator' => function (ServiceManager $sm)
            {
                $config = $sm->get('Config');
                if (! isset($config['session_id_generator'])) {
                    throw new Exception\ConfigNotFoundException('session_id_generator');
                }
                
                $generatorConfig = $config['session_id_generator'];
                
                if (! isset($generatorConfig['class'])) {
                    throw new Exception\ConfigNotFoundException('session_id_generator/class');
                }
                
                $className = $generatorConfig['class'];
                
                $options = array();
                if (isset($generatorConfig['options']) && is_array($generatorConfig['options'])) {
                    $options = $generatorConfig['options'];
                }
                
                return new $className($options);
            },
            
            /*
             * Authentication/Manager
             */
            'InoOicServer\AuthenticationManager' => function (ServiceManager $sm)
            {
                $config = $sm->get('Config');
                if (! isset($config['authentication'])) {
                    throw new Exception\ConfigNotFoundException('authentication');
                }
                
                $manager = new Authentication\Manager($config['authentication']);
                
                return $manager;
            },
            
            'InoOicServer\ClientAuthenticationManager' => function (ServiceManager $sm)
            {
                $config = $sm->get('Config');
                if (! isset($config['client_authentication_manager']) ||
                     ! is_array($config['client_authentication_manager'])) {
                    throw new Exception\ConfigNotFoundException('client_authentication_manager');
                }
                
                $manager = new Client\Authentication\Manager($config['client_authentication_manager']);
                
                return $manager;
            },
            
            'InoOicServer\AuthorizeContextManager' => function (ServiceManager $sm)
            {
                $contextStorage = $sm->get('InoOicServer\ContextStorage');
                $contextFactory = $sm->get('InoOicServer\AuthorizeContextFactory');
                $requestFactory = $sm->get('InoOicServer\AuthorizeRequestFactory');
                
                $manager = new AuthorizeContextManager($contextStorage, $requestFactory, $contextFactory);
                return $manager;
            },
            
            /*
             * OpenIdConnect/Dispatcher/Authorize
             */
            'InoOicServer\AuthorizeDispatcher' => function (ServiceManager $sm)
            {
                $dispatcher = new Dispatcher\Authorize();
                
                $dispatcher->setAuthorizeResponse($sm->get('InoOicServer\AuthorizeResponse'));
                $dispatcher->setClientRegistry($sm->get('InoOicServer\ClientRegistry'));
                $dispatcher->setSessionManager($sm->get('InoOicServer\SessionManager'));
                $dispatcher->setDataConnector($sm->get('InoOicServer\UserDataConnector'));
                
                return $dispatcher;
            },
            
            'InoOicServer\AuthorizeRequest' => function (ServiceManager $sm)
            {
                $httpRequest = $sm->get('Zend\HttpRequest');
                $request = new Simple($httpRequest);
                return $request;
            },
            
            /*
             * OpenIdConnect/Response/Authorize/
             */
            'InoOicServer\AuthorizeResponse' => function (ServiceManager $sm)
            {
                return new Response\Authorize\Simple($sm->get('Response'));
            },
            
            /*
             * OpenIdConnect/Dispatcher/Token
             */
            'InoOicServer\TokenDispatcher' => function (ServiceManager $sm)
            {
                $dispatcher = new Dispatcher\Token();
                
                $dispatcher->setClientRegistry($sm->get('InoOicServer\ClientRegistry'));
                $dispatcher->setSessionManager($sm->get('InoOicServer\SessionManager'));
                $dispatcher->setTokenRequest($sm->get('InoOicServer\TokenRequest'));
                $dispatcher->setTokenResponse($sm->get('InoOicServer\TokenResponse'));
                $dispatcher->setClientAuthenticationManager($sm->get('InoOicServer\ClientAuthenticationManager'));
                
                return $dispatcher;
            }, 
            
            /*
             * OpendIdConnect/Request/Token
             */
            'InoOicServer\TokenRequest' => function (ServiceManager $sm)
            {
                return new Request\Token($sm->get('Request'));
            }, 
            
            /*
             * OpenIdConnect/Response/Token
             */
            'InoOicServer\TokenResponse' => function (ServiceManager $sm)
            {
                return new Response\Token($sm->get('Response'));
            }, 
            
            /*
             * OpenIdConnect/Dispatcher/UserInfo
             */
            'InoOicServer\UserInfoDispatcher' => function (ServiceManager $sm)
            {
                $dispatcher = new Dispatcher\UserInfo();
                
                $dispatcher->setSessionManager($sm->get('InoOicServer\SessionManager'));
                $dispatcher->setUserInfoRequest($sm->get('InoOicServer\UserInfoRequest'));
                $dispatcher->setUserInfoResponse($sm->get('InoOicServer\UserInfoResponse'));
                $dispatcher->setUserInfoMapper($sm->get('InoOicServer\UserInfoMapper'));
                
                return $dispatcher;
            }, 
            
            /*
             * OpenIdConnect/Request/UserInfo
             */
            'InoOicServer\UserInfoRequest' => function (ServiceManager $sm)
            {
                return new Request\UserInfo($sm->get('Request'));
            }, 
            
            /*
             * OpenIdConnect/Response/UserInfo
             */
            'InoOicServer\UserInfoResponse' => function (ServiceManager $sm)
            {
                return new Response\UserInfo($sm->get('Response'));
            },

            /*
             * Input filter factory
             */
            'InoOicServer\InputFilterFactory' => function (ServiceManager $sm)
            {
                $factory = new Factory();
                
                $config = $sm->get('Config');
                if (isset($config['filter_invokables'])) {
                    $filterInvokables = $config['filter_invokables'];
                    $factory->setDefaultFilterChain(new FilterChain());
                    
                    $pluginManager = $factory->getDefaultFilterChain()->getPluginManager();
                    foreach ($filterInvokables as $filterName => $filterClass) {
                        $pluginManager->setInvokableClass($filterName, $filterClass);
                    }
                }
                
                return $factory;
            },
            
            'Zend\HttpRequest' => function (ServiceManager $sm)
            {
                $request = new \Zend\Http\PhpEnvironment\Request();
                return $request;
            }
        );
    }
}