<?php
namespace PhpIdServer\Controller;


class UserinfoController extends BaseController
{


    public function indexAction ()
    {
        $response = $this->getResponse();

        $response->getHeaders()
        ->addHeaders(array(
            'Content-Type' => 'application/json'
        ));

        $response->setContent(\Zend\Json\Encoder::encode(array(
            'endpoint' => 'userinfo'
        )));

        return $response;
    }
}