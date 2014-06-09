<?php

namespace InoOicServer\Oic;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use InoOicServer\Util\DateTimeUtil;


abstract class AbstractSessionFactory
{

    /**
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     * @var DateTimeUtil
     */
    protected $dateTimeUtil;


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