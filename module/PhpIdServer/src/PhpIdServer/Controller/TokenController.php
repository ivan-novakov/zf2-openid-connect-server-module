<?php
namespace PhpIdServer\Controller;


class TokenController extends BaseController
{


    public function indexAction ()
    {
        $response = $this->getResponse();
        
        $response->getHeaders()
            ->addHeaders(array(
            'Content-Type' => 'application/json'
        ));
        
        $response->setContent(\Zend\Json\Encoder::encode(array(
            'endpoint' => 'token'
        )));
        
        return $response;
    }
}