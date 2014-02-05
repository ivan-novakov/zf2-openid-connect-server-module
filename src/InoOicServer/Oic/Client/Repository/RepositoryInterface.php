<?php

namespace InoOicServer\Oic\Client\Repository;


/**
 * Client persistence.
 */
interface RepositoryInterface
{


    /**
     * Retrieves a client entity from the repository by its ID.
     * 
     * @param string $id
     * @return \InoOicServer\Oic\Client\Client
     */
    public function getClientById($id);
}