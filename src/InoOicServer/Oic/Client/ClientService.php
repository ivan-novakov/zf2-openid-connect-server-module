<?php

namespace InoOicServer\Oic\Client;

use InoOicServer\Oic\Token\TokenRequest;
use InoOicServer\Oic\Client\Mapper\MapperInterface;
use InoOicServer\Oic\Client\Authentication\CredentialsExtractor;
use InoOicServer\Oic\Authorize\AuthorizeRequest;


class ClientService implements ClientServiceInterface
{

    /**
     * @var MapperInterface
     */
    protected $clientMapper;

    /**
     * @var CredentialsExtractor
     */
    protected $credentialsExtractor;


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
     * @return CredentialsExtractor
     */
    public function getCredentialsExtractor()
    {
        if (! $this->credentialsExtractor instanceof CredentialsExtractor) {
            $this->credentialsExtractor = new CredentialsExtractor();
        }

        return $this->credentialsExtractor;
    }


    /**
     * @param CredentialsExtractor $credentialsExtractor
     */
    public function setCredentialsExtractor(CredentialsExtractor $credentialsExtractor)
    {
        $this->credentialsExtractor = $credentialsExtractor;
    }


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\Client\ClientServiceInterface::fetchClientFromAuthorizeRequest()
     * @throws Exception\MissingRedirectUriException
     * @throws Exception\RedirectUriMismatchException
     */
    public function fetchClientFromAuthorizeRequest(AuthorizeRequest $authorizeRequest)
    {
        $client = $this->fetchClient($authorizeRequest->getClientId());
        if (! $client instanceof Client) {
            // throw new Exception\UnknownClientException(sprintf("Unknown client '%s'",
            // $authorizeRequest->getClientId()));
            return null;
        }

        $redirectUri = $authorizeRequest->getRedirectUri();
        if (null === $redirectUri) {
            throw new Exception\MissingRedirectUriException('No redirect URI in authorize request');
        }

        if (! $client->hasRedirectUri($redirectUri)) {
            throw new Exception\RedirectUriMismatchException(sprintf("Invalid redirect URI '%s' for client '%s'", $redirectUri, $client->getId()));
        }

        return $client;
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


    public function resolveClientByTokenRequest(TokenRequest $request)
    {
        // extract credentials from HTTP request
        $credentials = $this->getCredentialsExtractor()->extract($request->getHttpRequest());
        if (null === $credentials) {
            return null;
        }

        // fetch client entity for the corresponding client ID
        $client = $this->getClientMapper()->getClientById($credentials->getClientId());
        if (null === $client) {
            throw new Exception\UnknownClientException(sprintf("Unkonwn client '%s'", $credentials->getClientId()));
        }

        // authenticate client - check secret and request URI
        if ($client->getSecret() !== $credentials->getClientSecret()) {
            // invalid secret
        }

        if (! $client->hasRedirectUri($credentials->getRedirectUri())) {
            // invalid redirect URI
        }

        // return client
    }
}