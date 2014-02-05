<?php

namespace InoOicServer\Oic\Client\Factory;

use InoOicServer\Oic\Client\Client;
use Zend\Stdlib\Hydrator;


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


    public function createClient(array $data)
    {
        $client = new Client();
        $this->getHydrator()->hydrate($data, $client);
        
        return $client;
    }
}