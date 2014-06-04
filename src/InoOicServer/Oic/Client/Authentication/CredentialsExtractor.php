<?php

namespace InoOicServer\Oic\Client\Authentication;

use Zend\Http;


class CredentialsExtractor
{


    /**
     * Extracts client credentials from the HTTP request.
     * 
     * @param Http\Request $httpRequest
     * @return Credentials|null
     */
    public function extract(Http\Request $httpRequest)
    {
        $credentials = $this->extractClientSecretPost($httpRequest);
        if ($credentials instanceof Credentials) {
            return $credentials;
        }
        
        $credentials = $this->extractClientSecretBasic($httpRequest);
        if ($credentials instanceof Credentials) {
            return $credentials;
        }
        
        return null;
    }


    public function extractClientSecretPost(Http\Request $httpRequest)
    {
        $postVars = $httpRequest->getPost();
        $clientId = $postVars->get(Authentication::REQUEST_FIELD_CLIENT_ID);
        $clientSecret = $postVars->get(Authentication::REQUEST_FIELD_CLIENT_SECRET);
        
        if ($this->isValidString($clientId) && $this->isValidString($clientSecret)) {
            return $this->createCredentials($clientId, $clientSecret, $this->extractRedirectUri($httpRequest), Authentication::TYPE_CLIENT_SECRET_POST);
        }
        
        return null;
    }


    public function extractClientSecretBasic(Http\Request $httpRequest)
    {
        $authorizationHeader = $httpRequest->getHeader('Authorization');
        if (! $authorizationHeader) {
            return null;
        }
        
        $parts = explode(' ', $authorizationHeader->getFieldValue());
        if (count($parts) != 2) {
            throw new Exception\CredentialsExtractionException('Invalid authorization header format');
        }
        
        $authType = trim($parts[0]);
        if ('basic' !== strtolower($authType)) {
            throw new Exception\CredentialsExtractionException(sprintf("Invalid authentication type '%s'", $authType));
        }
        
        $hash = trim($parts[1]);
        $decodedValue = base64_decode($hash);
        if (false === $decodedValue) {
            throw new Exception\CredentialsExtractionException('Error decoding base64 value');
        }
        
        $credentials = explode(':', $decodedValue);
        if (count($credentials) != 2) {
            throw new Exception\CredentialsExtractionException('Invalid basic authentication credentials');
        }
        
        return $this->createCredentials(trim($credentials[0]), trim($credentials[1]), $this->extractRedirectUri($httpRequest), Authentication::TYPE_CLIENT_SECRET_BASIC);
    }


    protected function extractRedirectUri(Http\Request $httpRequest)
    {
        return $httpRequest->getPost(Authentication::REQUEST_FIELD_REDIRECT_URI);
    }


    protected function createCredentials($clientId, $clientSecret, $redirectUri, $type)
    {
        $credentials = new Credentials();
        $credentials->setClientId($clientId);
        $credentials->setClientSecret($clientSecret);
        $credentials->setRedirectUri($redirectUri);
        $credentials->setType($type);
        
        return $credentials;
    }


    protected function isValidString($value)
    {
        return (is_string($value) && strlen($value) > 0);
    }
}