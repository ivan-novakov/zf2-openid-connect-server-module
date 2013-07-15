<?php

namespace InoOicServer\Session\IdGenerator;

use InoOicServer\Client\Client;
use InoOicServer\User\User;
use InoOicServer\General\Exception;


/**
 * The ID generator simply serializes the user ID, the client ID, adds the current time and a secret salt and
 * calculates the MD5 sum.
 *
 */
class Simple extends AbstractIdGenerator
{


    /**
     * {@inhertidoc}
     * @see \InoOicServer\Session\IdGenerator\IdGeneratorInterface::generateId()
     */
    public function generateId(Array $inputValues = array())
    {
        $secretSalt = $this->_options->get('secret_salt');
        if (! $secretSalt) {
            throw new Exception\MissingParameterException('secret_salt');
        }
        
        $inputValues[] = $secretSalt;
        $inputValues[] = $this->_options->get('time', time());
        
        return md5(implode('_', $inputValues));
    }
}