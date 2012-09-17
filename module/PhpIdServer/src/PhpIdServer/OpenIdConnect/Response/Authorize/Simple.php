<?php

namespace PhpIdServer\OpenIdConnect\Response\Authorize;

use PhpIdServer\OpenIdConnect\Response\Exception;


class Simple extends AbstractAuthorizeResponse
{


    public function setAuthorizationCode ($code)
    {
        $this->_addField(Field::CODE, $code);
    }


    public function setState ($state)
    {
        $this->_addField(Field::STATE, $state);
    }


    protected function _constructRedirectUri ()
    {
        if (NULL === $this->_redirectLocation) {
            throw new Exception\NoRedirectLocationException();
        }
        
        if (! $this->_isField(Field::CODE)) {
            throw new Exception\MissingFieldException(Field::CODE);
        }
        
        $uri = \Zend\Uri\UriFactory::factory($this->_redirectLocation);
        $uri->setQuery($this->_getFields());
        $uri->normalize();
        
        return $uri->toString();
    }
}