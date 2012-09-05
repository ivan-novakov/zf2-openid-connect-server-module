<?php

namespace PhpIdServerTest\Metadata;

use PhpIdServer\Metadata;

class StorageTest extends \PHPUnit_Framework_TestCase
{

    
    public function testDummy()
    {
        $mds = new Metadata\Storage();
        
        $this->assertInstanceOf('\PhpIdServer\Metadata\Storage', $mds);
    }
}