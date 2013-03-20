<?php

namespace PhpIdServer\Authentication\Controller\Exception;


class AuthenticationException extends \RuntimeException
{

    protected $_error = '';

    protected $_description = '';


    public function __construct ($error, $description = '')
    {
        $this->_error = $error;
        $this->_description = $description;
        
        parent::__construct(sprintf("%s (%s)", $error, $description));
    }


    public function getError ()
    {
        return $this->_error;
    }


    public function getDescription ()
    {
        return $this->_description;
    }
}