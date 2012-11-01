<?php

namespace PhpIdServerTest\Context;

use PhpIdServer\Context\AbstractContext;


class AbstractContextTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var AbstractContext
     */
    protected $_context = null;


    public function setUp ()
    {
        $this->_context = $this->getMockForAbstractClass('PhpIdServer\Context\AbstractContext');
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