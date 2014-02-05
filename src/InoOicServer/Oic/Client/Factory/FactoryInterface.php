<?php

namespace InoOicServer\Oic\Client\Factory;


/**
 * Client factory interface.
 */
interface FactoryInterface
{


    /**
     * Creates a client entity based on the supplied data.
     * 
     * @param array $data
     * @return \InoOicServer\Oic\Client\Client
     */
    public function createClient(array $data);
}