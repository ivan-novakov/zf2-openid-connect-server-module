<?php

namespace PhpIdServer\Controller;


class TokenController extends AbstractTokenController
{

    protected $_logIdent = 'token';


    public function indexAction ()
    {
        $this->_logInfo($_SERVER['REQUEST_URI']);
        
        $serviceManager = $this->_getServiceManager();
        $dispatcher = $serviceManager->get('TokenDispatcher');
        
        return $this->_dispatch($dispatcher);
    }
}