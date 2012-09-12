<?php

namespace PhpIdServerTest\User\Serializer;

use PhpIdServer\User;
use PhpIdServer\User\Serializer\Serializer;


class SerializerTest extends \PHPUnit_Framework_TestCase
{


    public function testGetAdapterWithInit ()
    {
        $serializer = $this->_getPhpSerializer();
        
        $adapter = $serializer->getAdapter();
        
        $this->assertInstanceOf('\Zend\Serializer\Adapter\AdapterInterface', $adapter);
    }


    public function testSerializeWithPhpSerialize ()
    {
        $serializer = $this->_getPhpSerializer();
        
        $user = new User\User(array(
            User\User::FIELD_ID => 'testuser'
        ));
        
        $serializedData = $serializer->serialize($user);
        $unserializedUser = $serializer->unserialize($serializedData);
        
        $this->assertInstanceOf('\PhpIdServer\User\User', $unserializedUser);
        $this->assertEquals($unserializedUser->toArray(), $user->toArray());
    }


    public function testInvalidUnserializationException ()
    {
        $this->setExpectedException('\PhpIdServer\User\Serializer\Exception\InvalidUnserializationException');
        
        $serializer = $this->_getPhpSerializer();
        
        $junkData = serialize(array(
            'junk'
        ));
        
        $serializer->unserialize($junkData);
    }


    protected function _getPhpSerializer ()
    {
        return new Serializer(array(
            'adapter' => array(
                'name' => 'PhpSerialize', 
                'options' => array()
            )
        ));
    }
}

