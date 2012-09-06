<?php
namespace PhpIdServer\Context;


class AbstractContext
{

    const STATE_INITIAL = 'initial';

    protected $_states = array(
        self::STATE_INITIAL
    );

    protected $_currentState = self::STATE_INITIAL;

    protected $_finalState = self::STATE_INITIAL;

    protected $_contextData = NULL;


    /**
     * Returns the current state.
     * 
     * @param string $state
     * @return string
     */
    public function getState ()
    {
        return $this->_currentState;
    }


    /**
     * Returns true, if the current state is the final state.
     * 
     * @return boolean
     */
    public function isFinalState ()
    {
        return ($this->_finalState == $this->getState());
    }


    /**
     * Sets the current state.
     * 
     * @param string $state
     * @throws Exception\UnknownStateException
     */
    public function setState ($state)
    {
        if (! in_array($state, $this->_states)) {
            throw new Exception\UnknownStateException($state);
        }
        
        $this->_currentState = $state;
    }


    /**
     * Sets context information.
     * 
     * @param string $label
     * @param mixed $value
     */
    public function setValue ($label, $value)
    {
        $this->getContextData()
            ->offsetSet($label, $value);
    }


    /**
     * Gets context information.
     * 
     * @param string $label
     * @return mixed|NULL
     */
    public function getValue ($label)
    {
        $data = $this->getContextData();
        
        if ($data->offsetExists($label)) {
            return $data->offsetGet($label);
        }
        
        return NULL;
    }


    /**
     * Returns the context data storage object.
     * 
     * @return \ArrayObject
     */
    public function getContextData ()
    {
        if (! ($this->_contextData instanceof \ArrayObject)) {
            $this->_contextData = new \ArrayObject(array());
        }
        
        return $this->_contextData;
    }
}