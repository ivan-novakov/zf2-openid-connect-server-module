<?php

namespace PhpIdServer\OpenIdConnect\Response;

use PhpIdServer\General\Exception as GeneralException;
use PhpIdServer\OpenIdConnect\Entity;


class Token extends AbstractTokenResponse
{

    /**
     * The token entity containing actual data.
     *
     * @var Entity\Token
     */
    protected $_tokenEntity = NULL;


    /**
     * Sets the token entity object.
     *
     * @param Entity\Token $entity
     */
    public function setTokenEntity (Entity\Token $entity)
    {
        $this->_tokenEntity = $entity;
    }


    /**
     * Returns the token entity object.
     *
     * @return Entity\Token
     */
    public function getTokenEntity ()
    {
        return $this->_tokenEntity;
    }


    /**
     * (non-PHPdoc)
     * @see AbstractResponse::getHttpResponse()
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
            $tokenEntity = $this->getTokenEntity();
            if (! $tokenEntity) {
                throw new GeneralException\MissingDependencyException('token entity');
            }
            
            $httpResponse->setContent($this->_createResponseContent($tokenEntity));
        }
        
        return $httpResponse;
    }


    /**
     * Creates and returns the response data.
     * 
     * @param Entity\Token $tokenEntity
     * @return string
     */
    protected function _createResponseContent (Entity\Token $tokenEntity)
    {
        if (! $tokenEntity->getAccessToken()) {
            throw Exception\MissingFieldException('access token');
        }
        
        return $this->_jsonEncode($tokenEntity->toArray());
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