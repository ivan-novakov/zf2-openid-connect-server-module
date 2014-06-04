<?php

namespace InoOicServerTest\Oic;


class AbstractRequestTest extends \PHPUnit_Framework_TestCase
{


    public function testSetHttpRequest()
    {
        $request = $this->getMockForAbstractClass('InoOicServer\Oic\AbstractRequest');
        $httpRequest = $this->getMock('Zend\Http\Request');
        $request->setHttpRequest($httpRequest);
        
        $this->assertSame($httpRequest, $request->getHttpRequest());
    }
}