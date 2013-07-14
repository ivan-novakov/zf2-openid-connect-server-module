<?php

namespace InoOicServer\Server;

use InoOicServer\Entity\Entity;


/**
 * Container for server information.
 * 
 * @method string getBaseUri()
 * @method string getServiceDocumentation()
 */
class ServerInfo extends Entity
{

    const FIELD_BASE_URI = 'base_uri';

    const FIELD_SERVICE_DOCUMENTATION = 'service_documentation';

    protected $_fields = array(
        self::FIELD_BASE_URI,
        self::FIELD_SERVICE_DOCUMENTATION
    );
}