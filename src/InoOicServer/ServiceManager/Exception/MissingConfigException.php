<?php

namespace InoOicServer\ServiceManager\Exception;


class MissingConfigException extends \RuntimeException
{


    public function __construct($configPath)
    {
        parent::__construct(sprintf("Missing configuration '%s'", $configPath));
    }
}