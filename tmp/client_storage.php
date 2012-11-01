<?php

use PhpIdServer\Client\Registry\Storage;

require 'bootstrap.php';

$storage = new Storage\SingleJsonFileStorage(array(
    'json_file' => APP_ROOT . 'data/client/metadata.json'
));

$data = $storage->getClientById('test-console-client');
_dump($data->getAuthenticationInfo());