<?php
namespace InoOicServer\Oic\AuthCode;

use InoOicServer\Oic\EntityHydrator;

class AuthCodeHydrator extends EntityHydrator
{
    // TEST
    public function convertValues(array $entityData)
    {
        $entityData = parent::convertValues($entityData);
        $entityData = $this->unsetFields($entityData, array(
            'session'
        ));
        
        return $entityData;
    }
}