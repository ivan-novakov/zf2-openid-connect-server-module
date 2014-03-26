<?php

namespace InoOicServerTest\Util\TokenGenerator;

use InoOicServer\Util\TokenGenerator\Simple;


class SimpleTest extends \PHPUnit_Framework_TestCase
{


    public function testGenerate()
    {
        $inputValues = array(
            'value1',
            'value2'
        );
        
        $secretSalt = 'blah';
        
        $generator = new Simple(array(
            Simple::OPT_SECRET_SALT => $secretSalt
        ));
        
        $usedValues = $inputValues + array(
            $secretSalt
        );
        $usedValues[] = $secretSalt;
        $expected = md5(implode('', $usedValues));
        
        $this->assertEquals($expected, $generator->generate($inputValues));
    }
}