<?php

namespace InoOicServer\Controller;

use Zend\Mvc\Controller\AbstractActionController;


class JwksController extends AbstractActionController
{


    public function indexAction()
    {
        /* @var $response \Zend\Http\Response */
        $response = $this->getResponse();
        
        $jwks = array(
            'keys' => array(
                array()
            )
        );
        
        $response->setContent(\Zend\Json\Json::encode($jwks));
        $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        
        return $response;
    }
}