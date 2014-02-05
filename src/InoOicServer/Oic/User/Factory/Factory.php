<?php

namespace InoOicServer\Oic\User\Factory;

use Zend\Stdlib\Hydrator;
use InoOicServer\Oic\User\User;


/**
 * Basic user factory implementation.
 */
class Factory implements FactoryInterface
{

    /**
     * @var Hydrator\HydratorInterface
     */
    protected $hydrator;


    /**
     * @return Hydrator\HydratorInterface
     */
    public function getHydrator()
    {
        if (! $this->hydrator instanceof Hydrator\HydratorInterface) {
            $this->hydrator = new Hydrator\ClassMethods();
        }
        
        return $this->hydrator;
    }


    /**
     * @param Hydrator\HydratorInterface $hydrator
     */
    public function setHydrator(Hydrator\HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
    }


    /**
     * {@inhertidoc}
     * @see \InoOicServer\Oic\User\Factory\FactoryInterface::createUser()
     */
    public function createUser(array $data)
    {
        $user = new User();
        $this->getHydrator()->hydrate($data, $user);
        
        return $user;
    }
}