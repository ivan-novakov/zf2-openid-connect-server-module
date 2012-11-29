<?php

namespace PhpIdServer\Authentication\Controller;

use PhpIdServer\User\User;
use PhpIdServer\Context\AuthorizeContext;


class ShibbolethController extends AbstractController
{

    const OPT_SYSTEM_ATTRIBUTES_MAP = 'system_attributes_map';

    const OPT_USER_ATTRIBUTES_MAP = 'user_attributes_map';

    const SYSTEM_VAR_SESSION_ID = 'session_id';

    /**
     * Server environment vars.
     * 
     * @var array
     */
    protected $_serverVars = null;

    protected $_systemVars = null;

    protected $_attributes = null;


    /**
     * Returns the server environment vars.
     * 
     * @return array
     */
    public function getServerVars ()
    {
        if (null === $this->_serverVars) {
            $this->_serverVars = $_SERVER;
        }
        
        return $this->_serverVars;
    }


    /**
     * Sets the server environments vars.
     * 
     * @param array $serverVars
     */
    public function setServerVars (array $serverVars)
    {
        $this->_serverVars = $serverVars;
    }


    /**
     * Returns true, if a Shibboleth session exists.
     * 
     * @return boolean
     */
    public function existsSession ()
    {
        return ($this->getSessionId() !== null);
    }


    /**
     * Returns the Shibboleth session ID or null if none exists.
     * 
     * @return integer|null
     */
    public function getSessionId ()
    {
        return $this->getSystemVar(self::SYSTEM_VAR_SESSION_ID);
    }


    /**
     * Returns a system var.
     * 
     * @param string $name
     * @return string|null
     */
    public function getSystemVar ($name)
    {
        $systemVars = $this->getSystemVars();
        if (isset($systemVars[$name])) {
            return $systemVars[$name];
        }
        
        return null;
    }


    /**
     * Returns all system vars.
     * 
     * @return array
     */
    public function getSystemVars ()
    {
        if (null === $this->_systemVars) {
            $systemVars = array();
            $systemVarsMap = $this->getOption(self::OPT_SYSTEM_ATTRIBUTES_MAP);
            if (is_array($systemVarsMap)) {
                $systemVars = $this->_getRemappedVars($this->getServerVars(), $systemVarsMap);
            }
            $this->_systemVars = $systemVars;
        }
        
        return $this->_systemVars;
    }


    /**
     * Returns the user attribute with the provided name.
     * 
     * @param string $name
     * @return string|null
     */
    public function getAttribute ($name)
    {
        $attributes = $this->getAttributes();
        if (isset($attributes[$name])) {
            return $attributes[$name];
        }
        
        return null;
    }


    /**
     * Returns all user attributes.
     * 
     * @return array
     */
    public function getAttributes ()
    {
        if (null === $this->_attributes) {
            $attributes = array();
            $attributesMap = $this->getOption(self::OPT_USER_ATTRIBUTES_MAP);
            if (is_array($attributesMap)) {
                $attributes = $this->_getRemappedVars($this->getServerVars(), $attributesMap);
            }
            
            $this->_attributes = $attributes;
        }
        
        return $this->_attributes;
    }


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\Authentication\Controller\AbstractController::authenticate()
     */
    public function authenticate ()
    {
        if (! $this->existsSession()) {
            throw new Exception\AuthenticationException('No Shibboleth session');
        }
        
        $attributes = $this->getAttributes();
        $user = $this->getUserFactory()
            ->createUser($attributes);

        if (! $user->getId()) {
            throw new Exception\AuthenticationException('No user identifier');
        }
        
        return $user;
    }


    /**
     * Remaps the keys of the provided array according to the provided mapping array.
     * 
     * @param array $vars
     * @param array $map
     * @return array
     */
    protected function _getRemappedVars (array $vars, array $map)
    {
        $mappedVars = array();
        foreach ($map as $inputVarName => $mappedVarName) {
            if (isset($vars[$inputVarName])) {
                $mappedVars[$mappedVarName] = $vars[$inputVarName];
            }
        }
        
        return $mappedVars;
    }
}