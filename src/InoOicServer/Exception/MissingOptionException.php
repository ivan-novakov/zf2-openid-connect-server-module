<?php

namespace InoOicServer\Exception;


class MissingOptionException extends \RuntimeException
{

    /**
     * @var string
     */
    protected $optionName;


    /**
     * Constructor.
     * 
     * @param string $optionName
     */
    public function __construct($optionName)
    {
        $this->setOptionName($optionName);
        parent::__construct(sprintf("Missing option '%s'", $this->getOptionName()));
    }


    /**
     * @return string
     */
    public function getOptionName()
    {
        return $this->optionName;
    }


    /**
     * @param string $optionName
     */
    public function setOptionName($optionName)
    {
        $this->optionName = $optionName;
    }
}