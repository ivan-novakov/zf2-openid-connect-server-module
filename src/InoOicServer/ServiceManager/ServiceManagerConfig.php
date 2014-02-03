<?php

namespace InoOicServer\ServiceManager;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config;
use Zend\InputFilter\Factory;
use Zend\Filter\FilterChain;
use Zend\Session\SessionManager as ZendSessionManager;
use Zend\Log;
use InoOicServer\Server\ServerInfo;
use InoOicServer\Session\SessionManager;
use InoOicServer\General\Exception as GeneralException;
use InoOicServer\Util\String;
use InoOicServer\User;
use InoOicServer\Authentication;
use InoOicServer\OpenIdConnect\Dispatcher;
use InoOicServer\OpenIdConnect\Response;
use InoOicServer\OpenIdConnect\Request;
use InoOicServer\Client;
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
            'InoOicServer\AuthorizeContextFactory' => 'InoOicServer\Context\AuthorizeContextFactory',
            'InoOicServer\UserDataConnectorFactory' => 'InoOicServer\User\DataConnector\DataConnectorFactory',
            'InoOicServer\UserValidatorFactory' => 'InoOicServer\User\Validator\ValidatorFactory'
        );
    }


    public function getFactories()
    {
        $smc = $this;
        return array(
            'InoOicServer\ContextStorage' => 'InoOicServer\Context\Storage\StorageFactory',
            'InoOicServer\SessionStorage' => 'InoOicServer\Session\Storage\StorageFactory',
            'InoOicServer\ClientRegistryStorage' => 'InoOicServer\Client\Registry\StorageFactory',
            'InoOicServer\ClientRegistry' => 'InoOicServer\Client\RegistryFactory',
            
            'InoOicServer\ServerInfo' => function (ServiceManager $sm)
            {
                $config = $sm->get('Config');
                if (! isset($config['oic_server']['oic_server_info']) || ! is_array($config['oic_server']['oic_server_info'])) {
                    throw new Exception\ConfigNotFoundException('oic_server/oic_server_info');
                }
                
                $serverInfo = new ServerInfo($config['oic_server']['oic_server_info']);
                return $serverInfo;
            },
        
            /*
             * Main logger object
             */
            'InoOicServer\Logger' => function (ServiceManager $sm) use($smc)
            {
                $config = $sm->get('Config');
                if (! isset($config['oic_server']['logger'])) {
                    throw new Exception\ConfigNotFoundException('logger');
                }
                
                $loggerConfig = $config['oic_server']['logger'];
                if (! isset($loggerConfig['writers'])) {
                    throw new Exception\ConfigNotFoundException('logger/writers');
                }
                
                $logger = new Log\Logger();
                
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
                                $formatter = new Log\Formatter\Simple($formatterConfig['format']);
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
            
            'InoOicServer\UaSessionManager' => function (ServiceManager $sm)
            {
                $config = $sm->get('Config');
                
                if (! isset($config['oic_server']['ua_session_manager']) || ! is_array($config['oic_server']['ua_session_manager'])) {
                    throw new Exception\ConfigNotFoundException('oic_server/ua_session_manager');
                }
                
                $uaSessionManagerConfig = $config['oic_server']['ua_session_manager'];
                
                $uaSessionConfig = null;
                if (isset($uaSessionManagerConfig['config'])) {
                    $class = isset($uaSessionManagerConfig['config']['class']) ? $uaSessionManagerConfig['config']['class'] : 'Zend\Session\Config\SessionConfig';
                    $options = isset($uaSessionManagerConfig['config']['options']) ? $uaSessionManagerConfig['config']['options'] : array();
                    $uaSessionConfig = new $class();
                    $uaSessionConfig->setOptions($options);
                }
                
                $uaSessionStorage = null;
                if (isset($uaSessionManagerConfig['storage'])) {
                    $class = $uaSessionManagerConfig['storage'];
                    $uaSessionStorage = new $class();
                }
                
                $uaSessionSaveHandler = null;
                if (isset($uaSessionManagerConfig['save_handler'])) {
                    // class should be fetched from service manager since it will require constructor arguments
                    $uaSessionSaveHandler = $sm->get($uaSessionManagerConfig['save_handler']);
                }
                
                $uaSessionManager = new ZendSessionManager($uaSessionConfig, $uaSessionStorage, $uaSessionSaveHandler);
                
                if (isset($uaSessionManagerConfig['validators'])) {
                    $chain = $uaSessionManager->getValidatorChain();
                    foreach ($uaSessionManagerConfig['validators'] as $validator) {
                        $validator = new $validator();
                        $chain->attach('session.validate', array(
                            $validator,
                            'isValid'
                        ));
                    }
                }
                $uaSessionManager->start();
                
                return $uaSessionManager;
            },
            
            'InoOicServer\SessionContainer' => function (ServiceManager $sm)
            {
                $uaSessionManager = $sm->get('InoOicServer\UaSessionManager');
                $container = new \Zend\Session\Container('InoOicServer', $uaSessionManager);
                return $container;
            },
       
            /*
             * User/Serializer
             */
            'InoOicServer\UserSerializer' => function (ServiceManager $sm)
            {
                $config = $sm->get('Config');
                if (! isset($config['oic_server']['user_serializer'])) {
                    throw new Exception\ConfigNotFoundException('user_serializer');
                }
                
                return new User\Serializer\Serializer($config['oic_server']['user_serializer']);
            }, 
            
            /*
             * User/UserFactory
             */
            'InoOicServer\UserFactory' => function (ServiceManager $sm)
            {
                $config = $sm->get('Config');
                if (! isset($config['oic_server']['user_factory'])) {
                    throw new Exception\ConfigNotFoundException('user_factory');
                }
                
                return new User\UserFactory($config['oic_server']['user_factory']);
            }, 
            
            /*
             * The default user data connector.
             * User/DataConnector/Chain
             */
            'InoOicServer\UserDataConnector' => function (ServiceManager $sm)
            {
                $config = $sm->get('Config');
                if (! isset($config['oic_server']['data_connectors'])) {
                    throw new Exception\ConfigNotFoundException('data_connectors');
                }
                
                $dataConnectorConfigs = $config['oic_server']['data_connectors'];
                $factory = $sm->get('InoOicServer\UserDataConnectorFactory');
                $chain = $factory->createDataConnector(array(
                    'class' => '\InoOicServer\User\DataConnector\Chain'
                ));
                foreach ($dataConnectorConfigs as $dataConnectorConfig) {
                    $chain->addDataConnector($factory->createDataConnector($dataConnectorConfig));
                }
                
                return $chain;
            },
            
            /*
             * The default user validator
             * User/Validator/ChainValidator
             */
            'InoOicServer\UserValidator' => function (ServiceManager $sm)
            {
                $config = $sm->get('Config');
                $validatorsConfig = array();
                
                if (isset($config['oic_server']['user_validators']) && is_array($config['oic_server']['user_validators'])) {
                    $validatorsConfig = $config['oic_server']['user_validators'];
                }
                
                /* @var $factory \InoOicServer\User\Validator\ValidatorFactory */
                $factory = $sm->get('InoOicServer\UserValidatorFactory');
                
                $validator = $factory->createValidator(array(
                    'class' => '\InoOicServer\User\Validator\ChainValidator'
                ));
                foreach ($validatorsConfig as $validatorConfig) {
                    $subValidator = $factory->createValidator($validatorConfig);
                    $subValidator->setSessionContainer($sm->get('InoOicServer\SessionContainer'));
                    $validator->addValidator($subValidator);
                }
                
                return $validator;
            },
            
            /*
             * User/UserInfo/Mapper/MapperInterface
             */
            'InoOicServer\UserInfoMapper' => function (ServiceManager $sm)
            {
                $config = $sm->get('Config');
                if (! isset($config['oic_server']['user_info_mapper'])) {
                    throw new Exception\ConfigNotFoundException('user_info_mapper');
                }
                
                $mapperConfig = $config['oic_server']['user_info_mapper'];
                if (! isset($mapperConfig['class'])) {
                    throw new Exception\ConfigNotFoundException('user_info_mapper / class');
                }
                
                $className = $mapperConfig['class'];
                if (! class_exists($className)) {
                    throw new GeneralException\InvalidClassException(sprintf("Non-existent class '%s'", $className));
                }
                
                return new $className();
            },
            
            'InoOicServer\OicSessionManager' => function (ServiceManager $sm) use($smc)
            {
                $config = $sm->get('Config');
                if (! isset($config['oic_server'][$smc::CONFIG_SESSION_MANAGER]) || ! is_array($config['oic_server'][$smc::CONFIG_SESSION_MANAGER])) {
                    throw new Exception\ConfigNotFoundException($smc::CONFIG_SESSION_MANAGER);
                }
                
                $uaSessionManager = new SessionManager($config['oic_server'][$smc::CONFIG_SESSION_MANAGER]);
                
                $uaSessionManager->setStorage($sm->get('InoOicServer\SessionStorage'));
                $uaSessionManager->setSessionIdGenerator($sm->get('InoOicServer\SessionIdGenerator'));
                $uaSessionManager->setTokenGenerator($sm->get('InoOicServer\TokenGenerator'));
                $uaSessionManager->setUserSerializer($sm->get('InoOicServer\UserSerializer'));
                
                return $uaSessionManager;
            },
            
            /*
             * Session/IdGenerator
             */
            'InoOicServer\SessionIdGenerator' => function (ServiceManager $sm)
            {
                $config = $sm->get('Config');
                if (! isset($config['oic_server']['session_id_generator'])) {
                    throw new Exception\ConfigNotFoundException('session_id_generator');
                }
                
                $generatorConfig = $config['oic_server']['session_id_generator'];
                
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
                if (! isset($config['oic_server']['authentication'])) {
                    throw new Exception\ConfigNotFoundException('authentication');
                }
                
                $manager = new Authentication\Manager($config['oic_server']['authentication']);
                
                return $manager;
            },
            
            'InoOicServer\ClientAuthenticationManager' => function (ServiceManager $sm)
            {
                $config = $sm->get('Config');
                if (! isset($config['oic_server']['client_authentication_manager']) || ! is_array($config['oic_server']['client_authentication_manager'])) {
                    throw new Exception\ConfigNotFoundException('client_authentication_manager');
                }
                
                $manager = new Client\Authentication\Manager($config['oic_server']['client_authentication_manager']);
                
                return $manager;
            },
            
            'InoOicServer\AuthorizeContextManager' => function (ServiceManager $sm)
            {
                $config = $sm->get('Config');
                $timeout = null;
                if (isset($config['oic_server']['context_authorize']['timeout'])) {
                    $timeout = intval($config['oic_server']['context_authorize']['timeout']);
                }
                
                $contextStorage = $sm->get('InoOicServer\ContextStorage');
                $sessionContainer = $sm->get('InoOicServer\SessionContainer');
                $contextStorage->setSessionContainer($sessionContainer);
                
                $contextFactory = $sm->get('InoOicServer\AuthorizeContextFactory');
                $requestFactory = $sm->get('InoOicServer\AuthorizeRequestFactory');
                
                $manager = new AuthorizeContextManager($contextStorage, $requestFactory, $contextFactory);
                if ($timeout) {
                    $manager->setTimeout($timeout);
                }
                
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
                $dispatcher->setSessionManager($sm->get('InoOicServer\OicSessionManager'));
                $dispatcher->setDataConnector($sm->get('InoOicServer\UserDataConnector'));
                $dispatcher->setUserValidator($sm->get('InoOicServer\UserValidator'));
                
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
                $dispatcher->setSessionManager($sm->get('InoOicServer\OicSessionManager'));
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
                
                $dispatcher->setSessionManager($sm->get('InoOicServer\OicSessionManager'));
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
                if (isset($config['oic_server']['filter_invokables'])) {
                    $filterInvokables = $config['oic_server']['filter_invokables'];
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