<?php

namespace InoOicServer\Oic\Client\Authentication;


class Authentication
{
    
    /*
     * Authentication types
     */
    const TYPE_CLIENT_SECRET_POST = 'client_secret_post';

    const TYPE_CLIENT_SECRET_BASIC = 'client_secret_basic';
    
    /*
     * Request fields
     */
    const REQUEST_FIELD_CLIENT_ID = 'client_id';

    const REQUEST_FIELD_CLIENT_SECRET = 'client_secret';

}