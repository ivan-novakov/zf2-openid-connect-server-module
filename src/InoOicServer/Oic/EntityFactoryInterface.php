<?php

namespace InoOicServer\Oic;


interface EntityFactoryInterface
{


    /**
     * Creates a new emtpy entity.
     * 
     * @return EntityInterface
     */
    public function createEmptyEntity();


    /**
     * Creates a new entity and hydrates it with the provided data.
     * 
     * @param array $entityData
     * @return EntityInterface
     */
    public function createEntityFromData(array $entityData);
}