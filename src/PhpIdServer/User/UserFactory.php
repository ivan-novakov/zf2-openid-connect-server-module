<?php

namespace PhpIdServer\User;

use PhpIdServer\General\Exception as GeneralException;
use PhpIdServer\Util\Options;


class UserFactory implements UserFactoryInterface
{

    const OPT_USER_CLASS = 'user_class';

    /**
     * Options.
     * 
     * @var Options
     */
    protected $_options = null;


    /**
     * Constructor.
     * 
     * @param array|\Traversable $options
     */
    public function __construct ($options = array())
    {
        $this->setOptions($options);
    }


    /**
     * Sets the options.
     * 
     * @param array|\Traversable $options
     */
    public function setOptions ($options = array())
    {
        $this->_options = new Options($options);
    }


    /**
     * Returns the required option value.
     * 
     * @param string $name
     * @param mixed $defaultValue
     * @return mixed
     */
    public function getOption ($name, $defaultValue = null)
    {
        return $this->_options->get($name, $defaultValue);
    }


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\User\UserFactoryInterface::createUser()
     */
    public function createUser (array $userData)
    {
        $userClass = $this->getOption(self::OPT_USER_CLASS);
        if (! $userClass) {
            throw new GeneralException\MissingConfigException(self::OPT_USER_CLASS);
        }
        
        if (! class_exists($userClass)) {
            throw new Exception\UnknownUserClassException($userClass);
        }
        
        $user = new $userClass($userData);
        
        if (! ($user instanceof UserInterface)) {
            throw new Exception\InvalidUserClassException(sprintf("The class '%s' does not implement the UserInterface"));
        }
        
        return $user;
    }
}