<?php

namespace InoOicServerTest\Oic\Authorize\Response;

use InoOicServer\Oic\Authorize\Response\ClientErrorResponse;


class ClientErrorResponseTest extends \PHPUnit_Framework_TestCase
{


    public function testSettersAndGetters()
    {
        $error = $this->getMock('InoOicServer\Oic\Error');
        
        $response = new ClientErrorResponse();
        $response->setError($error);
        
        $this->assertSame($error, $response->getError());
    }
}