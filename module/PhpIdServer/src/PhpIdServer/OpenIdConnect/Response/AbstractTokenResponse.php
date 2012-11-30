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
     * The error description.
     * 
     * @var string
     */
    protected $_errorDescription = NULL;

    /**
     * The default HTTP status code of the error response.
     *
     * @var integer
     */
    protected $_errorHttpStatusCode = 400;


    /**
     * Turns the response into error response.
     *
     * @param unknown_type $message
     */
    public function setError ($message, $description = NULL)
    {
        $this->_errorMessage = $message;
        $this->_errorDescription = $description;
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
     * @return string|NULL
     */
    public function getErrorMessage ()
    {
        return $this->_errorMessage;
    }


    /**
     * Returns the error description.
     * 
     * @return string|NULL
     */
    public function getErrorDescription ()
    {
        return $this->_errorDescription;
    }


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\OpenIdConnect\Response\AbstractResponse::getHttpResponse()
     * @return \Zend\Http\Response
     */
    public function getHttpResponse ()
    {
        $httpResponse = parent::getHttpResponse();
        $httpResponse->getHeaders()
            ->addHeaders(array(
            'Content-Type' => 'application/json'
        ));
        
        if ($this->isError()) {
            $httpResponse->setStatusCode($this->_errorHttpStatusCode);
            $httpResponse->setContent($this->_createErrorResponseContent());
        } else {
            $httpResponse->setContent($this->_createResponseContent());
        }
        
        return $httpResponse;
    }


    /**
     * Returns the response content data.
     * 
     * @return string
     */
    abstract protected function _createResponseContent ();


    /**
     * Creates and returns error response data.
     *
     * @return string
     */
    protected function _createErrorResponseContent ()
    {
        $errorData = array(
            'error' => $this->getErrorMessage()
        );
        
        if (($description = $this->getErrorDescription()) !== NULL) {
            $errorData['error_description'] = $description;
        }
        
        return $this->_jsonEncode($errorData);
    }


    /**
     * Encodes the provided array into JSON string.
     *
     * @param array $data
     * @return string
     */
    protected function _jsonEncode (Array $data)
    {
        return \Zend\Json\Json::encode($data);
    }
}