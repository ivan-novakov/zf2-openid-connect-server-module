<?php

namespace InoOicServerTest\Framework;


class Config
{


    static public function get ()
    {
        return new \Zend\Config\Config(require TESTS_CONFIG_FILE);
    }
}