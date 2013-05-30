<?php

namespace PhpIdServer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Json\Json;


class DiscoveryController extends AbstractActionController
{


    public function indexAction()
    {
        
        /* @var $response \Zend\Http\Response */
        $response = $this->getResponse();
        $response->getHeaders()->addHeaders(array(
            'Content-Type' => 'application/json'
        ));
        
        $issuer = 'https://connect.example.org';
        /*
         * http://openid.net/specs/openid-connect-discovery-1_0.html#ProviderMetadata
         */
        $configuration = Json::encode(array(
            'version' => '3.0',
            'issuer' => $issuer,
            'authorization_endpoint' => $issuer . '/oic/authorize',
            'token_endpoint' => $issuer . '/oic/token',
            'userinfo_endpoint' => $issuer . '/oic/userinfo',
            
            /*
             * Required
             */
            'jwks_uri' => '',
            'response_types_supported' => '',
            'subject_types_supported' => '',
            'id_token_signing_alg_values_supported' => ''
        ));
        
        $response->setContent($configuration);
        
        return $response;
    }
}