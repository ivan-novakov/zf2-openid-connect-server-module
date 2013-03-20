<?php

namespace PhpIdServer\Client\Registry;

use PhpIdServer\General\Factory\AbstractAdapterFactory;


class StorageFactory extends AbstractAdapterFactory
{

    const CONFIG_FIELD = 'client_registry_storage';


    protected function _getNs ()
    {
        return __NAMESPACE__ . '\Storage';
    }
}