<?php

namespace PhpIdServer\Controller;


class UserinfoController extends AbstractTokenController
{

    protected $logIdent = 'userinfo';


    public function indexAction ()
    {
        $this->logInfo($_SERVER['REQUEST_URI']);
        
        //$serviceManager = $this->_getServiceManager();
        //$dispatcher = $serviceManager->get('UserInfoDispatcher');
        $dispatcher = $this->getDispatcher();
        
        return $this->dispatchTokenRequest($dispatcher);
    }
}