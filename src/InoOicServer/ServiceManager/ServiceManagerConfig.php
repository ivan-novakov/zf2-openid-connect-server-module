<?php

namespace InoOicServer\ServiceManager;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config;


class ServiceManagerConfig extends Config
{


    public function getFactories()
    {
        return array();
    }
}