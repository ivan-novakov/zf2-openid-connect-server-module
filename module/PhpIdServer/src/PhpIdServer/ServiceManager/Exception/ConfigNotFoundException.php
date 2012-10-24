<?php

namespace PhpIdServer\ServiceManager\Exception;


class ConfigNotFoundException extends \RuntimeException
{


    public function __construct ($configItem)
    {
        parent::__construct(sprintf("Config item [%s] not found", $configItem));
    }
}