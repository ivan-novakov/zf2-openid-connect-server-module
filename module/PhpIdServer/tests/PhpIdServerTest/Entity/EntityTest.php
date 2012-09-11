<?php

namespace PhpIdServerTest\Entity;

use PhpIdServer\Entity\Entity;


class EntityTest extends \PHPUnit_Framework_TestCase
{


    public function testPopulate ()
    {
        $entity = new Entity();
        $entity->populate(array(
            'id' => 'myClientId', 
            'type' => 'public'
        ));
        
        $this->assertEquals('myClientId', $entity->getValue('id'));
        $this->assertEquals('public', $entity->getValue('type'));
    }


    public function testSetValue ()
    {
        $entity = new Entity();
        $entity->setValue('foo', 'bar');
        
        $this->assertEquals('bar', $entity->getValue('foo'));
    }
}