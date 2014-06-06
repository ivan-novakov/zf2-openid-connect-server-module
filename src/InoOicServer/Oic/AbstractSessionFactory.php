<?php

namespace InoOicServer\Oic;

use InoOicServer\Crypto\Hash\HashGeneratorInterface;
use InoOicServer\Util\DateTimeUtil;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;


abstract class AbstractSessionFactory
{

    /**
     * @var HashGeneratorInterface
     */
    protected $hashGenerator;

    /**
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     * @var DateTimeUtil
     */
    protected $dateTimeUtil;


    /**
     * Constructor.
     *
     * @param HashGeneratorInterface $hashGenerator
     * @param array $options
     */
    public function __construct(HashGeneratorInterface $hashGenerator)
    {
        $this->setHashGenerator($hashGenerator);
    }


    /**
     * @return HashGeneratorInterface
     */
    public function getHashGenerator()
    {
        return $this->hashGenerator;
    }


    /**
     * @param HashGeneratorInterface $hashGenerator
     */
    public function setHashGenerator(HashGeneratorInterface $hashGenerator)
    {
        $this->hashGenerator = $hashGenerator;
    }


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
     * @return DateTimeUtil
     */
    public function getDateTimeUtil()
    {
        if (! $this->dateTimeUtil instanceof DateTimeUtil) {
            $this->dateTimeUtil = new DateTimeUtil();
        }
        
        return $this->dateTimeUtil;
    }


    /**
     * @param DateTimeUtil $dateTimeUtil
     */
    public function setDateTimeUtil(DateTimeUtil $dateTimeUtil)
    {
        $this->dateTimeUtil = $dateTimeUtil;
    }
}