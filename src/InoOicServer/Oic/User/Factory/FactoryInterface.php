<?php

namespace InoOicServer\Oic\User\Factory;


/**
 * Interface for user factories.
 */
interface FactoryInterface
{


    /**
     * Creates a user based on the supplied data.
     * 
     * @param array $data
     * @return \InoOicServer\Oic\User\UserInterface
     */
    public function createUser(array $data);
}