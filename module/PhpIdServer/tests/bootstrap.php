<?php
use PhpIdServer\Module;
/*
define('ZF2_PATH', realpath(__DIR__ . '/../../../vendor/zendframework/zendframework/library/') . '/');
define('MODULE_PATH', dirname(__FILE__) . '/../');

define('TESTS_ROOT', dirname(__FILE__) . '/');
define('TMP_DIR', TESTS_ROOT . 'tmp/');

define('TESTS_CONFIG_FILE', TESTS_ROOT . '_files/tests.config.php');

require_once __DIR__ . '/../../../init_autoloader.php';

include ZF2_PATH . 'Zend/Loader/AutoloaderFactory.php';
Zend\Loader\AutoloaderFactory::factory(array(
    'Zend\Loader\StandardAutoloader' => array(
        'namespaces' => array(
            //'Zend' => ZF2_PATH . 'Zend/', 
            'PhpIdServer' => MODULE_PATH . 'src/PhpIdServer', 
            'PhpIdServerTest' => TESTS_ROOT . 'PhpIdServerTest/', 
            'MyUnit' => TESTS_ROOT . 'MyUnit'
        )
    )
));
*/

define('TESTS_ROOT', dirname(__FILE__) . '/');
define('TMP_DIR', TESTS_ROOT . 'tmp/');
define('TESTS_CONFIG_FILE', TESTS_ROOT . '_files/tests.config.php');

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/../Module.php';

$module = new Module();
\Zend\Loader\AutoloaderFactory::factory($module->getAutoloaderConfig());
\Zend\Loader\AutoloaderFactory::factory(array(
    'Zend\Loader\StandardAutoloader' => array(
        'namespaces' => array(
            'PhpIdServerTest' => TESTS_ROOT . 'PhpIdServerTest/', 
            'MyUnit' => TESTS_ROOT . 'MyUnit'
        )
    )
));

//--
function _dump($value)
{
    error_log(print_r($value, true)) . "\n";
}