<?php

return array(
    
    'db' => array(
        'dataset' => TESTS_ROOT . '_files/dataset.xml', 
        'table' => 'sessiontest',
        'pdo' => array(
            'raw_driver' => 'mysql', 
            'driver' => 'Pdo_Mysql', 
            'host' => 'localhost', 
            'username' => 'phpidserver', 
            'password' => 'heslp pro id server', 
            'database' => 'phpid'
        )
    )
);