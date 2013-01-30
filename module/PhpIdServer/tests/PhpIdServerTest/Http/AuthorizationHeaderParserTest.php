<?php

namespace PhpIdServerTest\Http;

use PhpIdServer\Http\AuthorizationHeaderParser;


class AuthorizationHeaderParserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var AuthorizationHeaderParser
     */
    protected $parser = null;


    public function setUp()
    {
        $this->parser = new AuthorizationHeaderParser();
    }


    public function testParseInvalidFormat()
    {
        $data = $this->parser->parse('singlevalue');
        
        $this->assertTrue($this->parser->isError());
    }


    public function testParseOk()
    {
        $data = $this->parser->parse('dummy key1=value1; key2=value2');
        
        $this->assertFalse($this->parser->isError());
        $this->assertInstanceOf('PhpIdServer\Client\Authentication\Data', $data);
        $this->assertSame('dummy', $data->getMethod());
        $this->assertSame(array(
            'key1' => 'value1',
            'key2' => 'value2'
        ), $data->getParams());
    }
}