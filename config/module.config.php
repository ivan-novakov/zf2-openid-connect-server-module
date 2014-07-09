<?php
return array(
    'oic_server' => array(
        'client_mapper' => array(
            'file' => __DIR__ . '/data/clients.php'
        ),
        
        'auth_session_service' => array(
            'salt' => 'auth session salt',
            'age' => 1800
        ),
        
        'session_service' => array(
            'salt' => 'session salt',
            'age' => 3600
        ),
        
        'auth_code_service' => array(
            'salt' => 'auth code salt',
            'age' => 7200
        )
    )
);
