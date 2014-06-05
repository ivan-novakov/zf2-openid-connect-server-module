<?php

namespace InoOicServerTest\Crypto\Hash;

use InoOicServer\Crypto\Hash\SimpleHashGenerator;


class SimpleHashGeneratorTest extends \PHPUnit_Framework_TestCase
{


    public function testGenerate()
    {
        $inputValues = array(
            'value1',
            'value2'
        );
        
        $secretSalt = 'blah';
        
        $generator = new SimpleHashGenerator(array(
            SimpleHashGenerator::OPT_SECRET_SALT => $secretSalt
        ));
        
        $usedValues = $inputValues + array(
            $secretSalt
        );
        $usedValues[] = $secretSalt;
        $expected = md5(implode('', $usedValues));
        
        $this->assertEquals($expected, $generator->generate($inputValues));
    }
}