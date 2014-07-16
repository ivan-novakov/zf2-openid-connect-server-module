<?php

namespace InoOicServer\Oic\Client\Factory;

use InoOicServer\Oic\AbstractEntityFactory;
use InoOicServer\Oic\Client\Client;


class Factory extends AbstractEntityFactory implements FactoryInterface
{


    public function createClient(array $data)
    {
        return $this->createEntityFromData($data);
    }


    public function createEmptyEntity()
    {
        return new Client();
    }
}