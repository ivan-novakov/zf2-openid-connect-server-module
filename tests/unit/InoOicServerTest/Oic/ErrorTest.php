<?php
namespace InoOicServerTest\Oic;

use InoOicServer\Oic\Error;

class ErrorTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $message = 'error';
        $description = 'description';

        $error = new Error($message, $description);
        $this->assertSame($message, $error->getMessage());
        $this->assertSame($description, $error->getDescription());
    }

    public function testSettersAndGetters()
    {
        $message = 'error';
        $description = 'description';

        $error = new Error();
        $error->setMessage($message);
        $error->setDescription($description);

        $this->assertSame($message, $error->getMessage());
        $this->assertSame($description, $error->getDescription());
    }
}