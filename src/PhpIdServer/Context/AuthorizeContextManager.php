<?php

namespace PhpIdServer\Context;

use PhpIdServer\OpenIdConnect\Request\Authorize\RequestFactory;


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
     * Initializes the context. If the request is initial, new context is created with a new authorize request. 
     * Otherwise the context is loaded from the storage.
     * 
     * @throws Exception\MissingContextException
     * @return AuthorizeContext
     */
    public function initContext()
    {
        if ($this->isInitialHttpRequest($this->httpRequest)) {
            $authorizeRequest = $this->requestFactory->createRequest($this->httpRequest);
            $context = $this->contextFactory->createContext();
            $context->setRequest($authorizeRequest);
        } else {
            $context = $this->loadContext();
            if (! $context instanceof AuthorizeContext) {
                throw new Exception\MissingContextException('Expected authorize context not found');
            }
        }
        
        return $context;
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
     * Returns true if the HTTP request is an initial authorize request (originating from the client).
     * 
     * @param \Zend\Http\Request $httpRequest
     * @return boolean
     */
    protected function isInitialHttpRequest(\Zend\Http\Request $httpRequest)
    {
        if (! $httpRequest->getQuery()->count()) {
            return false;
        }
        return true;
    }
}