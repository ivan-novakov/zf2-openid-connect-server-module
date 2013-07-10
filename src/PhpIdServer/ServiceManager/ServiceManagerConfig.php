<?php

namespace PhpIdServer\ServiceManager;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config;
use PhpIdServer\General\Exception as GeneralException;
use PhpIdServer\User\DataConnector\DataConnectorFactory;
use PhpIdServer\Util\String;
use PhpIdServer\User;
use PhpIdServer\Authentication;
use PhpIdServer\OpenIdConnect\Dispatcher;
use PhpIdServer\OpenIdConnect\Response;
use PhpIdServer\OpenIdConnect\Request;
use PhpIdServer\Client;
use Zend\InputFilter\Factory;
use Zend\Filter\FilterChain;
use PhpIdServer\Util\ErrorHandler;
use PhpIdServer\OpenIdConnect\Request\Authorize\Simple;
use PhpIdServer\Context\AuthorizeContextManager;


class ServiceManagerConfig extends Config
{


    public function getInvokables()
    {
        return array(
            'PhpIdServer\TokenGenerator' => 'PhpIdServer\Session\Hash\Generator\Simple',
            'PhpIdServer\AuthorizeRequestFactory' => 'PhpIdServer\OpenIdConnect\Request\Authorize\RequestFactory',
            'PhpIdServer\AuthorizeContextFactory' => 'PhpIdServer\Context\AuthorizeContextFactory'
        );
    }


    public function getFactories()
    {
        return array(
            'PhpIdServer\ContextStorage' => 'PhpIdServer\Context\Storage\StorageFactory',
            'PhpIdServer\SessionManager' => 'PhpIdServer\Session\SessionManagerFactory',
            'PhpIdServer\SessionStorage' => 'PhpIdServer\Session\Storage\StorageFactory',
            'PhpIdServer\ClientRegistryStorage' => 'PhpIdServer\Client\Registry\StorageFactory',
            'PhpIdServer\ClientRegistry' => 'PhpIdServer\Client\RegistryFactory',
            
            /*
             * Main logger object
             */
            'PhpIdServer\Logger' => function (ServiceManager $sm)
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
                        
                        if (isset($writerConfig['formatter']) && is_array($writerConfig['formatter']) && isset($writerConfig['formatter'])) {
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
            
            'PhpIdServer\ErrorHandler' => function (ServiceManager $sm)
            {
                return new ErrorHandler($sm->get('PhpIdServer\Logger'));
            },
            
            /*
             * User/Serializer
             */
            'PhpIdServer\UserSerializer' => function (ServiceManager $sm)
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
            'PhpIdServer\UserFactory' => function (ServiceManager $sm)
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
            'PhpIdServer\UserDataConnectorFactory' => function (ServiceManager $sm)
            {
                return new DataConnectorFactory();
            }, 
            
            /*
             * The default user data connector.
             * User/DataConnector/Chain
             */
            'PhpIdServer\UserDataConnector' => function (ServiceManager $sm)
            {
                $config = $sm->get('Config');
                if (! isset($config['data_connectors'])) {
                    throw new Exception\ConfigNotFoundException('data_connectors');
                }
                
                $dataConnectorConfigs = $config['data_connectors'];
                $factory = $sm->get('PhpIdServer\UserDataConnectorFactory');
                $chain = $factory->createDataConnector(array(
                    'class' => '\PhpIdServer\User\DataConnector\Chain'
                ));
                foreach ($dataConnectorConfigs as $dataConnectorConfig) {
                    $chain->addDataConnector($factory->createDataConnector($dataConnectorConfig));
                }
                
                return $chain;
            }, 
            
            /*
             * User/UserInfo/Mapper/MapperInterface
             */
            'PhpIdServer\UserInfoMapper' => function (ServiceManager $sm)
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
            
            /*
             * Session/IdGenerator
             */
            'PhpIdServer\SessionIdGenerator' => function (ServiceManager $sm)
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
            'PhpIdServer\AuthenticationManager' => function (ServiceManager $sm)
            {
                $config = $sm->get('Config');
                if (! isset($config['authentication'])) {
                    throw new Exception\ConfigNotFoundException('authentication');
                }
                
                $manager = new Authentication\Manager($config['authentication']);
                
                return $manager;
            },
            
            'PhpIdServer\ClientAuthenticationManager' => function (ServiceManager $sm)
            {
                $config = $sm->get('Config');
                if (! isset($config['client_authentication_manager']) || ! is_array($config['client_authentication_manager'])) {
                    throw new Exception\ConfigNotFoundException('client_authentication_manager');
                }
                
                $manager = new Client\Authentication\Manager($config['client_authentication_manager']);
                
                return $manager;
            },
            
            'PhpIdServer\AuthorizeContextManager' => function (ServiceManager $sm)
            {
                $contextStorage = $sm->get('PhpIdServer\ContextStorage');
                $contextFactory = $sm->get('PhpIdServer\AuthorizeContextFactory');
                $requestFactory = $sm->get('PhpIdServer\AuthorizeRequestFactory');
                
                $manager = new AuthorizeContextManager($contextStorage, $requestFactory, $contextFactory);
                return $manager;
            },
            
            /*
             * OpenIdConnect/Dispatcher/Authorize
             */
            'PhpIdServer\AuthorizeDispatcher' => function (ServiceManager $sm)
            {
                $dispatcher = new Dispatcher\Authorize();
                
                $dispatcher->setAuthorizeResponse($sm->get('PhpIdServer\AuthorizeResponse'));
                $dispatcher->setClientRegistry($sm->get('PhpIdServer\ClientRegistry'));
                $dispatcher->setSessionManager($sm->get('PhpIdServer\SessionManager'));
                $dispatcher->setDataConnector($sm->get('PhpIdServer\UserDataConnector'));
                
                return $dispatcher;
            },
            
            'PhpIdServer\AuthorizeRequest' => function (ServiceManager $sm)
            {
                $httpRequest = $sm->get('Zend\HttpRequest');
                $request = new Simple($httpRequest);
                return $request;
            },
            
            /*
             * OpenIdConnect/Response/Authorize/
             */
            'PhpIdServer\AuthorizeResponse' => function (ServiceManager $sm)
            {
                return new Response\Authorize\Simple($sm->get('Response'));
            },
            
            /*
             * OpenIdConnect/Dispatcher/Token
             */
            'PhpIdServer\TokenDispatcher' => function (ServiceManager $sm)
            {
                $dispatcher = new Dispatcher\Token();
                
                $dispatcher->setClientRegistry($sm->get('PhpIdServer\ClientRegistry'));
                $dispatcher->setSessionManager($sm->get('PhpIdServer\SessionManager'));
                $dispatcher->setTokenRequest($sm->get('PhpIdServer\TokenRequest'));
                $dispatcher->setTokenResponse($sm->get('PhpIdServer\TokenResponse'));
                $dispatcher->setClientAuthenticationManager($sm->get('PhpIdServer\ClientAuthenticationManager'));
                
                return $dispatcher;
            }, 
            
            /*
             * OpendIdConnect/Request/Token
             */
            'PhpIdServer\TokenRequest' => function (ServiceManager $sm)
            {
                return new Request\Token($sm->get('Request'));
            }, 
            
            /*
             * OpenIdConnect/Response/Token
             */
            'PhpIdServer\TokenResponse' => function (ServiceManager $sm)
            {
                return new Response\Token($sm->get('Response'));
            }, 
            
            /*
             * OpenIdConnect/Dispatcher/UserInfo
             */
            'PhpIdServer\UserInfoDispatcher' => function (ServiceManager $sm)
            {
                $dispatcher = new Dispatcher\UserInfo();
                
                $dispatcher->setSessionManager($sm->get('PhpIdServer\SessionManager'));
                $dispatcher->setUserInfoRequest($sm->get('PhpIdServer\UserInfoRequest'));
                $dispatcher->setUserInfoResponse($sm->get('PhpIdServer\UserInfoResponse'));
                $dispatcher->setUserInfoMapper($sm->get('PhpIdServer\UserInfoMapper'));
                
                return $dispatcher;
            }, 
            
            /*
             * OpenIdConnect/Request/UserInfo
             */
            'PhpIdServer\UserInfoRequest' => function (ServiceManager $sm)
            {
                return new Request\UserInfo($sm->get('Request'));
            }, 
            
            /*
             * OpenIdConnect/Response/UserInfo
             */
            'PhpIdServer\UserInfoResponse' => function (ServiceManager $sm)
            {
                return new Response\UserInfo($sm->get('Response'));
            },

            /*
             * Input filter factory
             */
            'PhpIdServer\InputFilterFactory' => function (ServiceManager $sm)
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