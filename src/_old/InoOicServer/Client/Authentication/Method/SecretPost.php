<?php

namespace InoOicServer\Client\Authentication\Method;

use InoOicServer\Client;
use Zend\Http;


/**
 * Server implementation of the "client_secret_post" client authentication method.
 * 
 * @see http://openid.net/specs/openid-connect-messages-1_0-20.html#client_authentication
 */
class SecretPost extends AbstractMethod
{

    const AUTH_OPTION_SECRET = 'secret';

    const OPT_CLIENT_ID_FIELD = 'client_id_field';

    const OPT_CLIENT_SECRET_FIELD = 'client_secret_field';

    protected $clientIdField = 'client_id';

    protected $clientSecretField = 'client_secret';


    /**
     * {@inhertidoc}
     * @see \InoOicServer\Client\Authentication\Method\MethodInterface::authenticate()
     */
    public function authenticate(Client\Authentication\Info $info, Http\Request $httpRequest)
    {
        /* @var $httpRequest \Zend\Http\Request */
        $postVars = $httpRequest->getPost();
        if (($clientId = $postVars->get($this->getClientIdFieldName())) === null) {
            return $this->createFailureResult('Missing client ID');
        }
        
        if (($clientSecret = $postVars->get($this->getClientSecretFieldName())) === null) {
            return $this->createFailureResult('Missing client secret');
        }
        
        if ($clientId !== $info->getClientId()) {
            return $this->createFailureResult(sprintf("Unknown client ID '%s'", $clientId));
        }
        
        if ($clientSecret !== $info->getOption(self::AUTH_OPTION_SECRET)) {
            return $this->createFailureResult('Invalid authorization');
        }
        
        return $this->createSuccessResult();
    }


    public function getClientIdFieldName()
    {
        if ($fieldName = $this->options->get(self::OPT_CLIENT_ID_FIELD)) {
            return $fieldName;
        }
        
        return $this->clientIdField;
    }


    public function getClientSecretFieldName()
    {
        if ($fieldName = $this->options->get(self::OPT_CLIENT_SECRET_FIELD)) {
            return $fieldName;
        }
        
        return $this->clientSecretField;
    }
}