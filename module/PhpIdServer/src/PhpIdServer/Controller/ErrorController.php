<?php
namespace PhpIdServer\Controller;


class ErrorController extends BaseController
{


    public function indexAction ()
    {
        $response = $this->getResponse();
        $response->setStatusCode(500);
        
        return $response;
    }
}