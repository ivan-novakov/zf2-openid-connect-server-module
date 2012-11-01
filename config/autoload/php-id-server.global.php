<?php

return array(
    'session_storage' => array(
        'type' => 'MysqlLite', 
        'options' => array(
            
            'session_table' => 'session', 
            'authorization_code_table' => 'authorization_code', 
            'access_token_table' => 'access_token', 
            'refresh_token_table' => 'refresh_token', 
            
            'adapter' => array(
                'driver' => 'Pdo_Mysql', 
                'host' => 'localhost', 
                'database' => 'phpidserver'
            )
        )
    ), 
    
    'session_id_generator' => array(
        'class' => '\PhpIdServer\Session\IdGenerator\Simple', 
        'options' => array()
    ), 
    
    'logger' => array(
        'writers' => array(
            'stream' => array(
                'name' => 'stream', 
                'options' => array(
                    'stream' => '/data/var/log/devel/phpid-server/phpid-server.log'
                ), 
                'filters' => array(
                    'priority' => 7
                ), 
                'formatter' => array(
                    'format' => '%timestamp% %priorityName% (%priority%): %message% %extra%', 
                    'dateTimeFormat' => 'Y-m-d H:i:s'
                )
            )
        )
    )
);