<?php

namespace PhpIdServer\OpenIdConnect\Response\Authorize;

use PhpIdServer\OpenIdConnect\Response\AbstractResponse;
use Zend\Uri\Uri;


class AbstractAuthorizeResponse extends AbstractResponse
{

    /**
     * A list of fields to be returned.
     * 
     * @var array
     */
    protected $fields = array();

    /**
     * The redirect URI.
     * 
     * @var string
     */
    protected $redirectLocation = NULL;


    public function getHttpResponse()
    {
        $this->httpResponse->getHeaders()->addHeaders(array(
            'Location' => $this->getRedirectUri()
        ));
        $this->httpResponse->setStatusCode(302);
        
        return parent::getHttpResponse();
    }


    /**
     * Sets the redirect URI.
     * 
     * @param string $location
     */
    public function setRedirectLocation($location)
    {
        $this->redirectLocation = $location;
    }


    public function getRedirectUri()
    {
        return $this->constructRedirectUri($this->redirectLocation);
    }


    protected function constructRedirectUri($uri = null, array $query = array())
    {
        $uri = new Uri($uri);
        
        if (! empty($query)) {
            $query = $uri->getQueryAsArray() + $query;
            $uri->setQuery($query);
        }
        
        return $uri;
    }


    protected function addField($fieldName, $fieldValue)
    {
        $this->fields[$fieldName] = $fieldValue;
    }


    protected function getFields()
    {
        return $this->fields;
    }


    protected function isField($fieldName)
    {
        return (isset($this->fields[$fieldName]));
    }
}