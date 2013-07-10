<?php

namespace InoOicServer\OpenIdConnect\Response\Authorize;

use InoOicServer\OpenIdConnect\Response\Exception;


class Simple extends AbstractAuthorizeResponse
{


    public function setAuthorizationCode($code)
    {
        $this->addField(Field::CODE, $code);
    }


    public function setState($state)
    {
        $this->addField(Field::STATE, $state);
    }


    public function getRedirectUri()
    {
        if (null === $this->redirectLocation) {
            throw new Exception\NoRedirectLocationException();
        }
        
        if (! $this->isField(Field::CODE)) {
            throw new Exception\MissingFieldException(Field::CODE);
        }
        
        return $this->constructRedirectUri($this->redirectLocation, $this->getFields());
    }
}