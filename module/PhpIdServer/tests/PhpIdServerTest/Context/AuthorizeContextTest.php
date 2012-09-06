<?php
namespace PhpIdServerTest\Context;
use PhpIdServer\Context;


class AuthorizeContextTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Context object.
     * 
     * @var Context\AuthorizeContext
     */
    protected $_context = NULL;


    public function setUp ()
    {
        $this->_context = new Context\AuthorizeContext();
    }


    public function testUnknownStateException ()
    {
        $this->setExpectedException('\PhpIdServer\Context\Exception\UnknownStateException');
        $this->_context->setState('unknown');
    }


    public function testGetContextData ()
    {
        $data = $this->_context->getContextData();
        $this->assertInstanceOf('\ArrayObject', $data);
    }


    public function testSetValue ()
    {
        $origValue = 'some data';
        $this->_context->setValue('label', $origValue);
        $value = $this->_context->getValue('label');
        $this->assertEquals($origValue, $value);
    }
}