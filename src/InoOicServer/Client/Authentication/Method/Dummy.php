<?php

namespace InoOicServer\Client\Authentication\Method;

use InoOicServer\Client;


/**
 * Dummy client authentication method simulating successfull authentication.
 */
class Dummy extends AbstractMethod
{

    const OPT_SUCCESS = 'success';


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Client\Authentication\Method\MethodInterface::authenticate()
     */
    public function authenticate(Client\Authentication\Info $info,\Zend\Http\Request $httpRequest)
    {
        if ($this->options->get(self::OPT_SUCCESS, false)) {
            return $this->createSuccessResult();
        }
        
        return $this->createFailureResult('dummy failure reason');
    }
}