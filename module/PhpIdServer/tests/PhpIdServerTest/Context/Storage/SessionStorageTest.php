<?php
namespace PhpIdServerTest\Context\Storage;
use PhpIdServer\Context;


class SessionStorageTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The session container.
     * 
     * @var Context\Storage\SessionStorage
     */
    protected $_storage = NULL;


    public function setUp ()
    {
        $this->_storage = new Context\Storage\SessionStorage(new \Zend\Session\Container('testContainer'));
    }


    public function tearDown ()
    {
        $this->_storage->clear();
    }


    public function testSave ()
    {
        $context = 'bar';
        $this->_storage->save($context);
        $context = $this->_storage->load();
        
        $this->assertEquals('bar', $context);
    }


    public function testLoadNull ()
    {
        $this->assertNull($this->_storage->load());
    }


    public function testClear ()
    {
        $this->_storage->save('some data');
        $this->_storage->clear();
        
        $this->assertNull($this->_storage->load());
    }
}