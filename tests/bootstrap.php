<?php

define('TESTS_ROOT', dirname(__FILE__) . '/');
define('TMP_DIR', TESTS_ROOT . 'tmp/');
define('TESTS_CONFIG_FILE', TESTS_ROOT . '_files/tests.config.php');
define('TESTS_FILES_DIR', TESTS_ROOT . '_files/');

$autoload = null;
$moduleAutoload = __DIR__ . '/../vendor/autoload.php';
$appAutoload = __DIR__ . '/../../../vendor/autoload.php';

if (file_exists($moduleAutoload)) {
    $autoload = $moduleAutoload;
} elseif (file_exists($appAutoload)) {
    $autoload = $appAutoload;
} else {
    die('No autoload available');
}

$loader = require $autoload;

// --
function _dump($value)
{
    error_log(print_r($value, true)) . "\n";
}