<?php
namespace InoOicServer\Client\Registry\Exception;


class ClientNotFoundException extends \Exception
{


    public function __construct ($clientId)
    {
        parent::__construct(sprintf("Client with ID '%s' not found in registry", $clientId));
    }
}