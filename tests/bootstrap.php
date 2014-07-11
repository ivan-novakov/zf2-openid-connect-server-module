<?php
define('TESTS_ROOT', __DIR__ . '/');
define('TMP_DIR', TESTS_ROOT . 'tmp/');
define('TESTS_CONFIG_DIR', TESTS_ROOT . '_config/');
define('TESTS_FILES_DIR', TESTS_ROOT . '_files/');

$autoload = null;
$moduleAutoload = __DIR__ . '/../vendor/autoload.php';
$appAutoload = __DIR__ . '/../../../autoload.php';

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