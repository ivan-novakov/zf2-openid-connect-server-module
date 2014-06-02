<?php

namespace InoOicServer\Server;

use InoOicServer\Entity\Entity;


/**
 * Container for server information.
 * 
 * @method string getBaseUri()
 * @method string getServiceDocumentation()
 * @method array getJwe()
 */
class ServerInfo extends Entity
{

    const FIELD_BASE_URI = 'base_uri';

    const FIELD_SERVICE_DOCUMENTATION = 'service_documentation';

    const FIELD_JWE = 'jwe';

    protected $_fields = array(
        self::FIELD_BASE_URI,
        self::FIELD_SERVICE_DOCUMENTATION,
        self::FIELD_JWE
    );
}