<?php

namespace InoOicServerTest\Server;

use InoOicServer\Server\ServerInfo;


class ServerInfoTest extends \PHPUnit_Framework_Testcase
{


    public function testConstructor()
    {
        $baseUri = 'http://server/base';
        $docs = 'http://server/docs';
        
        $info = new ServerInfo(
            array(
                ServerInfo::FIELD_BASE_URI => $baseUri,
                ServerInfo::FIELD_SERVICE_DOCUMENTATION => $docs
            ));
        
        $this->assertSame($baseUri, $info->getBaseUri());
        $this->assertSame($docs, $info->getServiceDocumentation());
    }
}