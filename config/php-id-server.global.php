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
    )
);