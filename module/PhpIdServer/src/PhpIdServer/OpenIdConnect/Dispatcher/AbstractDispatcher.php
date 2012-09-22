<?php

namespace PhpIdServer\OpenIdConnect\Dispatcher;

use PhpIdServer\Session\SessionManager;
use PhpIdServer\Client\Registry\Registry;
use PhpIdServer\OpenIdConnect\Request;
use PhpIdServer\OpenIdConnect\Response;


abstract class AbstractDispatcher
{
    
    /**
     * The client registry object.
     * 
     * @var Registry
     */
    protected $_clientRegistry = NULL;
    
    /**
     * The session manager object.
     * 
     * @var SessionManager
     */
    protected $_sessionManager = NULL;


    /**
     * Sets the client registry.
     * 
     * @param Registry $registry
     */
    public function setClientRegistry (Registry $registry)
    {
        $this->_clientRegistry = $registry;
    }


    /**
     * Returns the client registry.
     * 
     * @return Registry
     */
    public function getClientRegistry ()
    {
        return $this->_clientRegistry;
    }


    /**
     * Sets the session manager.
     * 
     * @param SessionManager $sessionManager
     */
    public function setSessionManager (SessionManager $sessionManager)
    {
        $this->_sessionManager = $sessionManager;
    }


    /**
     * Returns the session manager.
     * 
     * @return SessionManager
     */
    public function getSessionManager ()
    {
        return $this->_sessionManager;
    }
}