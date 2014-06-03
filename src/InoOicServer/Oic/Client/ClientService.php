<?php

namespace InoOicServer\Oic\Client;

use Zend\Http;
use InoOicServer\Oic\Client\Mapper\MapperInterface;


class ClientService implements ClientServiceInterface
{

    /**
     * @var MapperInterface
     */
    protected $clientMapper;


    /**
     * Constructor.
     * 
     * @param MapperInterface $clientMapper
     */
    public function __construct(MapperInterface $clientMapper)
    {
        $this->setClientMapper($clientMapper);
    }


    /**
     * @return MapperInterface
     */
    public function getClientMapper()
    {
        return $this->clientMapper;
    }


    /**
     * @param MapperInterface $clientMapper
     */
    public function setClientMapper(MapperInterface $clientMapper)
    {
        $this->clientMapper = $clientMapper;
    }


    /**
     * {@inhertidoc}
     * @see \InoOicServer\Oic\Client\ClientServiceInterface::fetchClient()
     */
    public function fetchClient($clientId, $redirectUri = null)
    {
        $client = $this->getClientMapper()->getClientById($clientId);
        if ($redirectUri && ! $client->hasRedirectUri($redirectUri)) {
            throw new Exception\RedirectUriMismatchException(sprintf("Client '%s' has no redirect URI '%s'", $clientId, $redirectUri));
        }
        
        return $client;
    }
    
    
    public function resolveClient(Http\Request $httpRequest)
    {
        // extract credentials from HTTP request
        // validate credentials - exists client ID and secret
        // fetch client entity for the corresponding client ID
        // authenticate client - check secret and request URI
        // return client
    }
}