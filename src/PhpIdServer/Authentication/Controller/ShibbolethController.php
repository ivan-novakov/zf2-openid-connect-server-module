<?php

namespace PhpIdServer\Authentication\Controller;

use PhpIdServer\Authentication\AttributeFilter;


/**
 * The controller authenticates users with Shibboleth. For the purpose, it is required to secure the URL 
 * of the controller using a Shibboleth service provider.
 */
class ShibbolethController extends AbstractController
{

    /**
     * Array for mapping system server variables to internal system variables.
     */
    const OPT_SYSTEM_ATTRIBUTES_MAP = 'system_attributes_map';

    /**
     * Array for mapping user attributes from the server environment to user entity attributes.
     */
    const OPT_USER_ATTRIBUTES_MAP = 'user_attributes_map';

    /**
     * Configuration array for the attribute filter object.
     */
    const OPT_ATTRIBUTE_FILTER = 'attribute_filter';

    const SYSTEM_VAR_SESSION_ID = 'session_id';

    /**
     * Server environment vars (usually $_SERVER)
     * 
     * @var array
     */
    protected $_serverVars = null;

    /**
     * Variables associated with the Shibboleth session.
     * 
     * @var array
     */
    protected $_systemVars = null;

    /**
     * Variables holding the user attributes.
     * 
     * @var array
     */
    protected $_attributes = null;

    /**
     * Attribute filter.
     * 
     * @var AttributeFilter
     */
    protected $_attributeFilter = null;


    /**
     * Sets the attribute filter.
     * 
     * @param AttributeFilter $attributeFilter
     */
    public function setAttributeFilter(AttributeFilter $attributeFilter)
    {
        $this->_attributeFilter = $attributeFilter;
    }


    /**
     * Returns the attribute filter.
     * 
     * @return AttributeFilter
     */
    public function getAttributeFilter()
    {
        if (! ($this->_attributeFilter instanceof AttributeFilter)) {
            $config = $this->getOption(self::OPT_ATTRIBUTE_FILTER);
            if (is_array($config)) {
                $this->_attributeFilter = new AttributeFilter($config, $this->getUserInputFilterFactory());
            }
        }
        
        return $this->_attributeFilter;
    }


    /**
     * Returns a specific server environment variable.
     * 
     * @param string $name
     * @return string|null
     */
    public function getServerVar($name)
    {
        $serverVars = $this->getServerVars();
        if (isset($serverVars[$name])) {
            return $serverVars[$name];
        }
        
        return null;
    }


    /**
     * Returns the server environment vars.
     * 
     * @return array
     */
    public function getServerVars()
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
    public function setServerVars(array $serverVars)
    {
        $this->_serverVars = $serverVars;
    }


    /**
     * Returns true, if a Shibboleth session exists.
     * 
     * @return boolean
     */
    public function existsSession()
    {
        return ($this->getSessionId() !== null);
    }


    /**
     * Returns the Shibboleth session ID or null if none exists.
     * 
     * @return integer|null
     */
    public function getSessionId()
    {
        return $this->getSystemVar(self::SYSTEM_VAR_SESSION_ID);
    }


    /**
     * Returns a system var.
     * 
     * @param string $name
     * @return string|null
     */
    public function getSystemVar($name)
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
    public function getSystemVars()
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
    public function getAttribute($name)
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
    public function getAttributes()
    {
        if (null === $this->_attributes) {
            $attributes = array();
            $attributesMap = $this->getOption(self::OPT_USER_ATTRIBUTES_MAP);
            $serverVars = $this->getServerVars();
            
            try {
                $attributeFilter = $this->getAttributeFilter();
                if ($attributeFilter instanceof AttributeFilter) {
                    $serverVars = $attributeFilter->filterValues($serverVars);
                }
            } catch (\Exception $e) {
                throw new Exception\InvalidUserDataException(sprintf("Invalid user data: %s", $e->getMessage()));
            }
            
            if (is_array($attributesMap)) {
                $attributes = $this->_getRemappedVars($serverVars, $attributesMap);
            }
            
            $this->_attributes = $attributes;
        }
        
        return $this->_attributes;
    }


    /**
     * {@inheritdoc}
     * @see \PhpIdServer\Authentication\Controller\AbstractController::authenticate()
     */
    public function authenticate()
    {
        if (! $this->existsSession()) {
            throw new Exception\SessionNotFoundException('No Shibboleth session');
        }
        
        $attributes = $this->getAttributes();
        
        $user = $this->getUserFactory()->createUser($attributes);
        
        if (! $user->getId()) {
            throw new Exception\MissingUserIdentityException('No user identifier');
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
    protected function _getRemappedVars(array $vars, array $map)
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