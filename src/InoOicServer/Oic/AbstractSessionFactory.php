<?php

namespace InoOicServer\Oic;

use InoOicServer\Crypto\Hash\HashGeneratorInterface;
use InoOicServer\Util\DateTimeUtil;


abstract class AbstractSessionFactory
{

    /**
     * @var HashGeneratorInterface
     */
    protected $hashGenerator;

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