<?php
namespace InoOicServer\Oic\Authorize;

use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\HydratorInterface;

class AuthorizeRequestFactory implements AuthorizeRequestFactoryInterface
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
     * @see \InoOicServer\Oic\Authorize\Request\RequestFactoryInterface::createRequest()
     */
    public function createRequest(array $values)
    {
        return $this->getHydrator()->hydrate($values, new AuthorizeRequest());
    }
}