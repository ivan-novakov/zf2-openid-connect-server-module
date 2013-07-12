<?php

namespace InoOicServer\Server;

use InoOicServer\Entity\Entity;


class ServerInfo extends Entity
{

    const FIELD_SERVER_VERSION = 'server_version';

    const FIELD_BASE_URI = 'base_uri';

    protected $_fields = array(
        self::FIELD_SERVER_VERSION,
        self::FIELD_BASE_URI
    );
}