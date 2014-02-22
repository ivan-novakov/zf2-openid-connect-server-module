<?php

namespace InoOicServer\Oic\Authorize\Context;


/**
 * Interface for authorize context factories.
 */
interface ContextFactoryInterface
{


    /**
     * Creates an authorize context instance.
     * 
     * @return \InoOicServer\Oic\Authorize\Context
     */
    public function createContext();
}