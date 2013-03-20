<?php

namespace PhpIdServer\OpenIdConnect\Dispatcher;

use PhpIdServer\General\Exception as GeneralException;
use PhpIdServer\Session\SessionManager;
use PhpIdServer\Client\Registry\Registry;
use PhpIdServer\OpenIdConnect\Request;
use PhpIdServer\OpenIdConnect\Response;


abstract class AbstractDispatcher implements DispatcherInterface
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
     * @throws GeneralException\MissingDependencyException
     * @return Registry
     */
    public function getClientRegistry ($throwException = false)
    {
        if ($throwException && ! ($this->_clientRegistry instanceof Registry)) {
            throw new GeneralException\MissingDependencyException('client registry');
        }
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
     * @throws GeneralException\MissingDependencyException
     * @return SessionManager
     */
    public function getSessionManager ($throwException = false)
    {
        if ($throwException && ! ($this->_sessionManager instanceof SessionManager)) {
            throw new GeneralException\MissingDependencyException('session manager');
        }
        return $this->_sessionManager;
    }
}