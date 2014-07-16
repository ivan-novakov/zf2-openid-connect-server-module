<?php

namespace InoOicServer\Oic\User\Factory;

use InoOicServer\Oic\AbstractEntityFactory;
use InoOicServer\Oic\User\User;


/**
 * Basic user factory implementation.
 */
class Factory extends AbstractEntityFactory implements FactoryInterface
{


    /**
     * {@inhertidoc}
     * @see \InoOicServer\Oic\User\Factory\FactoryInterface::createUser()
     */
    public function createUser(array $data)
    {
        return $this->createEntityFromData($data);
    }


    /**
     * {@inhertidoc}
     * @see \InoOicServer\Oic\EntityFactoryInterface::createEmptyEntity()
     */
    public function createEmptyEntity()
    {
        return new User();
    }
}