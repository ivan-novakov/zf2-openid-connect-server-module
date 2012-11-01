<?php

chdir(dirname(__DIR__));
define('APP_ROOT', dirname(__DIR__) . '/');

require 'init_autoloader.php';

Zend\Loader\AutoloaderFactory::factory(array(
    'Zend\Loader\StandardAutoloader' => array(
        'namespaces' => array(
            'PhpIdServer' => 'module/PhpIdServer/src/PhpIdServer/'
        )
    )
));

//--
function _dump ($value)
{
    error_log(print_r($value, true));
}