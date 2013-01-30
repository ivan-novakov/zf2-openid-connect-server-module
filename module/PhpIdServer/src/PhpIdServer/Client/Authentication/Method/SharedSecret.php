<?php

namespace PhpIdServer\Client\Authentication\Method;

use PhpIdServer\Client;


/**
 * Client authentication method, which uses a shared secret.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class SharedSecret extends AbstractMethod
{

    const AUTH_INFO_SECRET = 'auth';

    const AUTH_DATA_SECRET = 'auth';


    /**
     * {@inheritdoc}
     * @see \PhpIdServer\Client\Authentication\Method\MethodInterface::authenticate()
     */
    public function authenticate(Client\Authentication\Info $info, Client\Authentication\Data $data)
    {
        $localSecret = $info->getOption(self::AUTH_INFO_SECRET);
        if (null === $localSecret) {
            return $this->createFailureResult(
                sprintf("Missing local client configuration field '%s'", self::AUTH_INFO_SECRET));
        }
        
        $remoteSecret = $data->getParam(self::AUTH_DATA_SECRET);
        if (null === $remoteSecret) {
            return $this->createFailureResult(
                sprintf("Missing remote authentication parameter '%s'", self::AUTH_DATA_SECRET));
        }
        
        if ($localSecret != $remoteSecret) {
            return $this->createFailureResult('Remote secret and local secret do not match');
        }
        
        return $this->createSuccessResult();
    }
}