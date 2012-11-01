<?php

use PhpIdServer\Session\Session;

use PhpIdServer\Session\Storage\MysqlLite;

require 'bootstrap.php';

$ss = new MysqlLite(array(
    'adapter' => array(
        'driver' => 'Pdo_Mysql', 
        'host' => 'localhost', 
        'username' => 'phpidserver', 
        'password' => 'heslp pro id server', 
        'database' => 'phpid'
    )
));

$session = Session::create('testuser', 'testclient', 123, 'dummy', array(
    'foo' => 'bar'
));

$ss->saveSession($session);