<?php
use InoOicServer\Module;

define('TESTS_ROOT', dirname(__FILE__) . '/');
define('TMP_DIR', TESTS_ROOT . 'tmp/');
define('TESTS_CONFIG_FILE', TESTS_ROOT . '_files/tests.config.php');

$autoload = null;
$moduleAutoload = __DIR__ . '/../vendor/autoload.php';
$appAutoload = __DIR__ . '/../../../../vendor/autoload.php';

if (file_exists($moduleAutoload)) {
    $autoload = $moduleAutoload;
} elseif (file_exists($appAutoload)) {
    $autoload = $appAutoload;
} else {
    die('No autoload available');
}

require __DIR__ . '/../Module.php';

$module = new Module();
\Zend\Loader\AutoloaderFactory::factory($module->getAutoloaderConfig());
\Zend\Loader\AutoloaderFactory::factory(array(
    'Zend\Loader\StandardAutoloader' => array(
        'namespaces' => array(
            'InoOicServerTest' => TESTS_ROOT . 'InoOicServerTest/',
            'MyUnit' => TESTS_ROOT . 'MyUnit'
        )
    )
));

// --
function _dump($value)
{
    error_log(print_r($value, true)) . "\n";
}