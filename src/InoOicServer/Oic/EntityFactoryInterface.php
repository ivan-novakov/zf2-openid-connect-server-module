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
}