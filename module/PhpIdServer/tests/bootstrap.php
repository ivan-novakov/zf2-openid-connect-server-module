<?php

define('ZF2_PATH', realpath(__DIR__ . '/../../../vendor/zendframework/zendframework/library/') . '/');
define('MODULE_PATH', dirname(__FILE__) . '/../');

define('TESTS_ROOT', dirname(__FILE__) . '/');
define('TMP_DIR', TESTS_ROOT . 'tmp/');

include ZF2_PATH . 'Zend/Loader/AutoloaderFactory.php';
Zend\Loader\AutoloaderFactory::factory(array(
    'Zend\Loader\StandardAutoloader' => array(
        'namespaces' => array(
            'Zend' => ZF2_PATH . 'Zend/', 
            'PhpIdServer' => MODULE_PATH . 'src/PhpIdServer'
        )
    )
));

//--
function _dump ($value)
{
    error_log(print_r($value, true)) . "\n";
}