<?php

namespace InoOicServerTest\Oic\Authorize;

use InoOicServer\Oic\Authorize\Context;


class ContextTest extends \PHPUnit_Framework_Testcase
{


    public function testSettersAndGetters()
    {
        $uniqueId = 'abc';
        $request = $this->getMock('InoOicServer\Oic\Authorize\Request\Request');
        $authStatus = $this->getMock('InoOicServer\Oic\User\Authentication\Status');
        
        $context = new Context();
        $context->setUniqueId($uniqueId);
        $context->setAuthorizeRequest($request);
        $context->setAuthStatus($authStatus);
        
        $this->assertSame($uniqueId, $context->getUniqueId());
        $this->assertSame($request, $context->getAuthorizeRequest());
        $this->assertSame($authStatus, $context->getAuthStatus());
    }
}