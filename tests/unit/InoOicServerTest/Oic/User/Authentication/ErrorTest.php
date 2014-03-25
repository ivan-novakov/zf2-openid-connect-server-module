<?php

namespace InoOicServerTest\Oic\User\Authentication;

use InoOicServer\Oic\User\Authentication\Error;


class ErrorTest extends \PHPUnit_Framework_Testcase
{


    public function testConstructor()
    {
        $message = 'error message';
        $description = 'error description';
        $extraData = array(
            'foo' => 'bar'
        );
        
        $error = new Error($message, $description, $extraData);
        
        $this->assertSame($message, $error->getMessage());
        $this->assertSame($description, $error->getDescription());
        $this->assertSame($extraData, $error->getExtraData());
    }
}