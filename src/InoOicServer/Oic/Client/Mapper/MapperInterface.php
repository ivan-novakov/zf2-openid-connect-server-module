<?php

namespace InoOicServer\Oic\Client\Mapper;


/**
 * Client persistence.
 */
interface MapperInterface
{


    /**
     * Retrieves a client entity from the repository by its ID.
     * 
     * @param string $id
     * @return \InoOicServer\Oic\Client\Client
     */
    public function getClientById($id);
}