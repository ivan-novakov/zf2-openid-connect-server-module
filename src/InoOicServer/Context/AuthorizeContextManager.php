<?php

namespace InoOicServer\Context;

use InoOicServer\OpenIdConnect\Request\Authorize\RequestFactory;
use InoOicServer\OpenIdConnect\Request;
use DateTime;


/**
 * Handles authorize context initialization and storage.
 */
class AuthorizeContextManager
{

    /**
     * Context storage interface.
     * @var Storage\StorageInterface
     */
    protected $storage;

    /**
     * Context factory.
     * @var AuthorizeContextFactory
     */
    protected $contextFactory;

    /**
     * Authorize request factory.
     * @var RequestFactory
     */
    protected $requestFactory;

    /**
     * Zend HTTP request.
     * @var \Zend\Http\Request
     */
    protected $httpRequest;

    /**
     * @var Request\Authorize\Simple
     */
    protected $authorizeRequest;

    /**
     * @var integer
     */
    protected $timeout = 600;


    /**
     * Constructor.
     * 
     * @param Storage\StorageInterface $storage
     * @param AuthorizeContextFactory $contextFactory
     * @param RequestFactory $requestFactory
     * @param \Zend\Http\Request $httpRequest
     */
    public function __construct(Storage\StorageInterface $storage, RequestFactory $requestFactory, AuthorizeContextFactory $contextFactory = null,\Zend\Http\Request $httpRequest = null)
    {
        if (null === $contextFactory) {
            $contextFactory = new AuthorizeContextFactory();
        }
        
        if (null === $httpRequest) {
            $httpRequest = new \Zend\Http\PhpEnvironment\Request();
        }
        
        $this->storage = $storage;
        $this->contextFactory = $contextFactory;
        $this->requestFactory = $requestFactory;
        $this->httpRequest = $httpRequest;
    }


    /**
     * @return integer
     */
    public function getTimeout()
    {
        return $this->timeout;
    }


    /**
     * @param integer $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }


    /**
     * Initializes the context. If the request is initial, new context is created with a new authorize request. 
     * Otherwise the context is loaded from the storage.
     * 
     * @throws Exception\MissingContextException
     * @return AuthorizeContext
     */
    public function initContext()
    {
        if (! $this->isInitialHttpRequest($this->httpRequest)) {
            $context = $this->loadContext();
            if ($context instanceof AuthorizeContext) {
                return $context;
            }
        }
        
        return $this->createContext();
    }


    /**
     * Loads the context from the storage.
     * 
     * @return AuthorizeContext|null
     */
    public function loadContext()
    {
        return $this->storage->load();
    }


    /**
     * Saves the context to the storage.
     * 
     * @param AuthorizeContext $context
     */
    public function persistContext(AuthorizeContext $context)
    {
        $this->storage->save($context);
    }


    /**
     * Removes the current context from the storage.
     */
    public function unpersistContext()
    {
        $this->storage->clear();
    }


    /**
     * Updates the context with the current authorize request.
     * 
     * @param AuthorizeContext $context
     */
    public function updateContextRequest(AuthorizeContext $context)
    {
        $context->setRequest($this->getAuthorizeRequest());
    }


    /**
     * @return Request\Authorize\Simple
     */
    public function getAuthorizeRequest()
    {
        if (! $this->authorizeRequest instanceof Request\Authorize\Simple) {
            $this->authorizeRequest = $this->requestFactory->createRequest($this->httpRequest);
        }
        
        return $this->authorizeRequest;
    }


    public function isExpiredContext(AuthorizeContext $context, DateTime $now = null)
    {
        if (null === $now) {
            $now = new DateTime('now');
        }
        
        $authenticationInfo = $context->getAuthenticationInfo();
        if (! $authenticationInfo) {
            return true;
        }
        
        $authTime = $authenticationInfo->getTime();
        
        $authTimestamp = $authTime->getTimestamp();
        $expireTimestamp = $authTimestamp + $this->getTimeout();
        
        if ($now->getTimestamp() > $expireTimestamp) {
            return true;
        }
        
        return false;
    }


    protected function createContext()
    {
        // $authorizeRequest = $this->requestFactory->createRequest($this->httpRequest);
        $authorizeRequest = $this->getAuthorizeRequest();
        $context = $this->contextFactory->createContext();
        $context->setRequest($authorizeRequest);
        
        return $context;
    }


    /**
     * Returns true if the HTTP request is an initial authorize request (originating from the client).
     * 
     * @param \Zend\Http\Request $httpRequest
     * @return boolean
     */
    protected function isInitialHttpRequest(\Zend\Http\Request $httpRequest)
    {
        if (! $httpRequest->getQuery()->get('client_id')) {
            return false;
        }
        
        return true;
    }
}