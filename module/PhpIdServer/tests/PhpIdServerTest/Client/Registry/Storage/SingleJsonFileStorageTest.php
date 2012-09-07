<?php
namespace PhpIdServerTest\Client\Registry\Storage;
use PhpIdServer\Client\Registry\Storage;


class SingleJsonFileStorageTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Storage object.
     * 
     * @var Storage\SingleJsonFileStorage
     */
    protected $_storage = NULL;

    protected $_jsonFile = NULL;


    public function setUp ()
    {
        $this->_jsonFile = TMP_DIR . 'test.json';
        
        $this->_storage = new Storage\SingleJsonFileStorage(array(
            'json_file' => $this->_jsonFile
        ));
    }


    public function tearDown ()
    {
        if (file_exists($this->_jsonFile)) {
            unlink($this->_jsonFile);
        }
    }


    public function testLoadData ()
    {
        $jsonData = \Zend\Json\Json::encode(array(
            'foo' => 'bar'
        ));
        
        file_put_contents($this->_jsonFile, $jsonData);
        
        $readData = $this->_storage->getRawData();
        
        $this->assertInternalType('array', $readData);
        $this->assertArrayHasKey('foo', $readData);
        $this->assertEquals('bar', $readData['foo']);
    }


    public function testGetClientById ()
    {
        $jsonData = \Zend\Json\Json::encode(array(
            'clients' => array(
                array(
                    'id' => 'test-client', 
                    'type' => 'public', 
                    'redirect_uri' => 'http://uri', 
                    'authentication' => array(
                        'type' => 'secret', 
                        'options' => array(
                            'secret' => 'xxx'
                        )
                    )
                )
            )
        ));
        
        file_put_contents($this->_jsonFile, $jsonData);
        
        $client = $this->_storage->getClientById('test-client');
        
        $this->assertInstanceOf('\PhpIdServer\Client\Client', $client);
        $this->assertEquals('test-client', $client->getId());
    }
}