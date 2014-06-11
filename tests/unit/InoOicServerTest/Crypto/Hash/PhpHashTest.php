<?php

namespace InoOicServerTest\Crypto\Hash;

use InoOicServer\Crypto\Hash\PhpHash;


class PhpHashTest extends \PHPUnit_Framework_TestCase
{


    public function testSetDefaultAlgo()
    {
        $algo = 'sha1234';
        $generator = new PhpHash();
        $generator->setDefaultAlgo($algo);
        
        $this->assertSame($algo, $generator->getDefaultAlgo());
    }


    /**
     * @dataProvider generateHashDataProvider
     */
    public function testGenerateHash($data, $salt, $algo, $hash)
    {
        $defaultAlgo = 'sha1';
        $generator = new PhpHash();
        $generator->setDefaultAlgo($defaultAlgo);
        
        $this->assertSame($hash, $generator->generateHash($data, $salt, $algo));
    }
    
    /*
     * 
     */
    public function generateHashDataProvider()
    {
        return array(
            array(
                'data' => 'testdata',
                'salt' => 'testsalt',
                'algo' => null,
                'hash' => '57fe9264a8ca1a66bea144b641f1483563f8b33e'
            ),
            array(
                'data' => 'testdata',
                'salt' => 'testsalt',
                'algo' => 'sha1',
                'hash' => '57fe9264a8ca1a66bea144b641f1483563f8b33e'
            ),
            array(
                'data' => 'testdata',
                'salt' => 'testsalt',
                'algo' => 'md5',
                'hash' => 'f0a6ae1429261f2884e16801a2dfbde2'
            ),
            array(
                'data' => 'testdata',
                'salt' => 'testsalt',
                'algo' => 'sha256',
                'hash' => '4a726bf1b30b4b89aee34b86e9954023d3edbba62a41ed922fe80c7f9fce19ff'
            ),
            array(
                'data' => 'testdata',
                'salt' => null,
                'algo' => 'sha1',
                'hash' => '44115646e09ab3481adc2b1dc17be10dd9cdaa09'
            )
        );
    }
}