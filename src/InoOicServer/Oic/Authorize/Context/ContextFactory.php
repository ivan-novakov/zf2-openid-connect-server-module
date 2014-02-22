<?php

namespace InoOicServer\Oic\Authorize\Context;

use InoOicServer\Oic\Authorize\Context;


/**
 * Authorize context factory.
 */
class ContextFactory implements ContextFactoryInterface
{


    /**
     * {@inhertidoc}
     * @see \InoOicServer\Oic\Authorize\Context\ContextFactoryInterface::createContext()
     */
    public function createContext()
    {
        return new Context();
    }
}