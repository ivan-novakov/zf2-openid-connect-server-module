<?php

namespace InoOicServerTest\Util;

use InoOicServer\Util\FileReader;


class FileReaderTest extends \PHPUnit_Framework_Testcase
{

    /**
     * @var FileReader
     */
    protected $fileReader;

    protected $dataDir;


    public function setUp()
    {
        $this->fileReader = new FileReader();
        $this->dataDir = TESTS_FILES_DIR . 'file_reader/';
        chmod($this->mockFile('unreadable_file.txt'), 0000);
    }


    public function tearDown()
    {
        chmod($this->mockFile('unreadable_file.txt'), 0644);
    }


    public function testCheckFileWithNonExistentFile()
    {
        $this->setExpectedException('InoOicServer\Exception\InvalidFileException', 'Non-existent file');
        
        $this->fileReader->checkFile('foo.txt');
    }


    public function testCheckFileWithUnreadableFile()
    {
        $this->setExpectedException('InoOicServer\Exception\InvalidFileException', 'Cannot read file');
        
        $this->fileReader->checkFile($this->mockFile('unreadable_file.txt'));
    }


    public function testCheckWithNotAFile()
    {
        $this->setExpectedException('InoOicServer\Exception\InvalidFileException', 'Not a file');
        
        $this->fileReader->checkFile(__DIR__);
    }


    public function testReadFileAsArrayWithInvalidFormat()
    {
        $this->setExpectedException('InoOicServer\Exception\InvalidFileFormatException', 'Invalid file format');
        
        $this->fileReader->readFileAsArray($this->mockFile('invalid_format_file.txt'));
    }


    public function testReadFileAsArrayWithValidData()
    {
        $expectedData = array(
            'foo1' => 'bar2',
            'foo2' => 'bar2'
        );
        
        $data = $this->fileReader->readFileAsArray($this->mockFile('valid_file.php'));
        
        $this->assertEquals($expectedData, $data);
    }


    protected function mockFile($filename)
    {
        return $this->dataDir . $filename;
    }
}