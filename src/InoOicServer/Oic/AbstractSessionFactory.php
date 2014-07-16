<?php

namespace InoOicServer\Oic;

use InoOicServer\Util\DateTimeUtil;


abstract class AbstractSessionFactory extends AbstractEntityFactory
{

    /**
     * @var DateTimeUtil
     */
    protected $dateTimeUtil;


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