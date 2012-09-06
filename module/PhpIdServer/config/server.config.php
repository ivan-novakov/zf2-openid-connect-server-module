<?php

return array(
    
    'logger' => array(
        'writers' => array(
            array(
                'name' => 'stream', 
                'options' => array(
                    'stream' => '/data/var/log/devel/phpid-server/phpid-server.log'
                )
            )
        )
    ), 
    
    'authentication' => array(
        'handler' => array(
            'adapter' => 'PhpIdServer\Authentication\Handler\Shibboleth', 
            'options' => array(
                'endpoint' => array(
                    'name' => 'auth-handler-shibboleth', 
                    'route' => '/shibboleth'
                )
            )
        )
    )
);