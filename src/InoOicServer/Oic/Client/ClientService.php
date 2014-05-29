<?php

namespace InoOicServer\Oic\Client;

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
}