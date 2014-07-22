<?php
namespace InoOicServer\Oic\AuthSession;

use InoOicServer\Oic\EntityHydrator;

class AuthSessionHydrator extends EntityHydrator
{
    
    // TEST
    public function convertValues(array $entityData)
    {
        $entityData = parent::convertValues($entityData);
        $entityData = $this->unsetFields($entityData, array(
            'user'
        ));
        
        return $entityData;
    }
}