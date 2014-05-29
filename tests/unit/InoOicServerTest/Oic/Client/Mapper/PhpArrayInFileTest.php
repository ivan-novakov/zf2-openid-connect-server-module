<?php

namespace InoOicServerTest\Oic\Client\Mapper;

use InoOicServer\Oic\Client\Mapper\PhpArrayInFile;


class PhpArrayInFileTest extends \PHPUnit_Framework_Testcase
{


    public function testGetClientByIdWithMissingFileOption()
    {
        $this->setExpectedException('InoOicServer\Exception\MissingOptionException', 'Missing option');
        
        $repository = new PhpArrayInFile(array());
        $repository->getClientById('foo');
    }


    public function testGetClientByIdWithNotFound()
    {
        $filename = 'foo.php';
        $data = array(
            array(
                'id' => 'other'
            )
        );
        
        $fileReader = $this->createFileReaderMock($filename, $data);
        
        $repository = new PhpArrayInFile(array(
            'file' => $filename
        ));
        $repository->setFileReader($fileReader);
        
        $this->assertNull($repository->getClientById('foo'));
    }


    public function testGetClientByIdWithClientFound()
    {
        $filename = 'foo.php';
        $data = array(
            array(
                'id' => 'other'
            ),
            array(
                'id' => 'foo',
                'secret' => 'passwd'
            )
        );
        
        $fileReader = $this->createFileReaderMock($filename, $data);
        
        $repository = new PhpArrayInFile(array(
            'file' => $filename
        ));
        $repository->setFileReader($fileReader);
        
        $client = $repository->getClientById('foo');
        
        $this->assertInstanceOf('InoOicServer\Oic\Client\Client', $client);
        $this->assertSame('foo', $client->getId());
        $this->assertSame('passwd', $client->getSecret());
    }
    
    /*
     * 
     */
    protected function createFileReaderMock($filename = null, $data = null)
    {
        $fileReader = $this->getMock('InoOicServer\Util\FileReader');
        if ($filename && $data) {
            $fileReader->expects($this->once())
                ->method('readFileAsArray')
                ->with($filename)
                ->will($this->returnValue($data));
        }
        return $fileReader;
    }
}