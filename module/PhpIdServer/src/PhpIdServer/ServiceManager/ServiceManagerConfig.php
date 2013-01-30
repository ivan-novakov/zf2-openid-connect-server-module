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


class ServiceManagerConfig extends Config
{


    public function getFactories ()
    {
        return array(
            
            /*
             * Main logger object
             */
            'Logger' => function  (ServiceManager $sm)
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
            
            /*
             * User/Serializer
             */
            'UserSerializer' => function  (ServiceManager $sm)
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
            'UserFactory' => function  (ServiceManager $sm)
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
            'UserDataConnectorFactory' => function  (ServiceManager $sm)
            {
                return new DataConnectorFactory();
            }, 
            
            /*
             * The default user data connector.
             * User/DataConnector/Chain
             */
            'UserDataConnector' => function  (ServiceManager $sm)
            {
                $config = $sm->get('Config');
                if (! isset($config['data_connectors'])) {
                    throw new Exception\ConfigNotFoundException('data_connectors');
                }
                
                $dataConnectorConfigs = $config['data_connectors'];
                $factory = $sm->get('UserDataConnectorFactory');
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
            'UserInfoMapper' => function  (ServiceManager $sm)
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
            'SessionIdGenerator' => function  (ServiceManager $sm)
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
            'AuthenticationManager' => function  (ServiceManager $sm)
            {
                $config = $sm->get('Config');
                if (! isset($config['authentication'])) {
                    throw new Exception\ConfigNotFoundException('authentication');
                }
                
                $manager = new Authentication\Manager($config['authentication']);
                $manager->setContext($sm->get('AuthorizeContext'));
                
                return $manager;
            }, 
            
            'ClientAuthenticationManager' => function  (ServiceManager $sm)
            {
                $config = $sm->get('Config');
                if (! isset($config['client_authentication_manager']) || ! is_array($config['client_authentication_manager'])) {
                    throw new Exception\ConfigNotFoundException('client_authentication_manager');
                }
                
                $manager = new Client\Authentication\Manager($config['client_authentication_manager']);
                
                return $manager;
            },
            
            /*
             * OpenIdConnect/Dispatcher/Authorize
             */
            'AuthorizeDispatcher' => function  (ServiceManager $sm)
            {
                $dispatcher = new Dispatcher\Authorize();
                
                $dispatcher->setContext($sm->get('AuthorizeContext'));
                $dispatcher->setAuthorizeResponse($sm->get('AuthorizeResponse'));
                $dispatcher->setClientRegistry($sm->get('ClientRegistry'));
                $dispatcher->setSessionManager($sm->get('SessionManager'));
                $dispatcher->setDataConnector($sm->get('UserDataConnector'));
                
                return $dispatcher;
            }, 
            
            /*
             * OpenIdConnect/Response/Authorize/
             */
            'AuthorizeResponse' => function  (ServiceManager $sm)
            {
                return new Response\Authorize\Simple($sm->get('Response'));
            },
            
            /*
             * OpenIdConnect/Dispatcher/Token
             */
            'TokenDispatcher' => function  (ServiceManager $sm)
            {
                $dispatcher = new Dispatcher\Token();
                
                $dispatcher->setClientRegistry($sm->get('ClientRegistry'));
                $dispatcher->setSessionManager($sm->get('SessionManager'));
                $dispatcher->setTokenRequest($sm->get('TokenRequest'));
                $dispatcher->setTokenResponse($sm->get('TokenResponse'));
                $dispatcher->setClientAuthenticationManager($sm->get('ClientAuthenticationManager'));
                
                return $dispatcher;
            }, 
            
            /*
             * OpendIdConnect/Request/Token
             */
            'TokenRequest' => function  (ServiceManager $sm)
            {
                return new Request\Token($sm->get('Request'));
            }, 
            
            /*
             * OpenIdConnect/Response/Token
             */
            'TokenResponse' => function  (ServiceManager $sm)
            {
                return new Response\Token($sm->get('Response'));
            }, 
            
            /*
             * OpenIdConnect/Dispatcher/UserInfo
             */
            'UserInfoDispatcher' => function  (ServiceManager $sm)
            {
                $dispatcher = new Dispatcher\UserInfo();
                
                $dispatcher->setSessionManager($sm->get('SessionManager'));
                $dispatcher->setUserInfoRequest($sm->get('UserInfoRequest'));
                $dispatcher->setUserInfoResponse($sm->get('UserInfoResponse'));
                $dispatcher->setUserInfoMapper($sm->get('UserInfoMapper'));
                
                return $dispatcher;
            }, 
            
            /*
             * OpenIdConnect/Request/UserInfo
             */
            'UserInfoRequest' => function  (ServiceManager $sm)
            {
                return new Request\UserInfo($sm->get('Request'));
            }, 
            
            /*
             * OpenIdConnect/Response/UserInfo
             */
            'UserInfoResponse' => function  (ServiceManager $sm)
            {
                return new Response\UserInfo($sm->get('Response'));
            }
        );
    }
}