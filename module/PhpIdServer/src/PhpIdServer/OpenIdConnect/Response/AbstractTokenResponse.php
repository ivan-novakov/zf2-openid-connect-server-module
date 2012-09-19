<?php

namespace PhpIdServer\OpenIdConnect\Response;

use PhpIdServer\OpenIdConnect\Entity;


abstract class AbstractTokenResponse extends AbstractResponse
{

    /**
     * Error message.
     *
     * @var string
     */
    protected $_errorMessage = NULL;


    /**
     * Turns the response into error response.
     *
     * @param unknown_type $message
     */
    public function setError ($message)
    {
        $this->_errorMessage = $message;
    }


    /**
     * Returns true, if the response is an error response.
     *
     * @return boolean
     */
    public function isError ()
    {
        return (NULL !== $this->_errorMessage);
    }


    /**
     * Returns the error message.
     *
     * @return string
     */
    public function getErrorMessage ()
    {
        return $this->_errorMessage;
    }
}