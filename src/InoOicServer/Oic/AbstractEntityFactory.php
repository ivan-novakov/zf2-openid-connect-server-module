<?php

namespace InoOicServer\Oic;

use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\HydratorInterface;


abstract class AbstractEntityFactory implements EntityFactoryInterface
{

    /**
     * @var HydratorInterface
     */
    protected $hydrator;


    /**
     * @return HydratorInterface
     */
    public function getHydrator()
    {
        if (! $this->hydrator instanceof HydratorInterface) {
            $this->hydrator = new ClassMethods();
        }
        
        return $this->hydrator;
    }


    /**
     * @param HydratorInterface $hydrator
     */
    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
    }


    /**
     * {@inhertidoc}
     * @see \InoOicServer\Oic\EntityFactoryInterface::createEntityFromData()
     */
    public function createEntityFromData(array $entityData)
    {
        return $this->getHydrator()->hydrate($entityData, $this->createEmptyEntity());
    }
}