<?php

return array(
    'nette_debug' => array(
        'enabled' => false, 
        'mode' => false,  // true = production|false = development|null = autodetect|IP address(es) csv/array
        'strict' => true,  // bool = cause immediate death|int = matched against error severity
        'log' => "",  // bool = enabled|Path to directory eg. data/logs
        'email' => "",  // in production mode notifies the recipient
        'max_depth' => 3,  // nested levels of array/object
        'max_len' => 150,  // max string display length
        /*
        'template_map' => array( // merge templates if enabled
            'error/index' => __DIR__ . '/../view/error/index.phtml'
        )
        */
    )
);