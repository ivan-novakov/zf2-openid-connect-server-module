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
            $httpResponse->setStatusCode(400);
            $httpResponse->setContent($this->_createErrorResponseContent());
        } else {
            $httpResponse->setContent($this->_createResponseContent());
        }
        
        return $httpResponse;
    }


    /**
     * Creates and returns error response data.
     *
     * @return string
     */
    protected function _createErrorResponseContent ()
    {
        return $this->_jsonEncode(array(
            'error' => $this->getErrorMessage()
        ));
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