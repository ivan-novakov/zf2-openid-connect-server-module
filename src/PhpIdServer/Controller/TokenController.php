<?php

namespace PhpIdServer\Controller;


class TokenController extends AbstractTokenController
{

    protected $logIdent = 'token';


    public function indexAction ()
    {
        $this->logInfo($_SERVER['REQUEST_URI']);
        
        //$serviceManager = $this->_getServiceManager();
        //$dispatcher = $serviceManager->get('TokenDispatcher');
        $dispatcher = $this->getDispatcher();
        
        return $this->dispatchTokenRequest($dispatcher);
    }
}