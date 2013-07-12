<?php

namespace InoOicServer\Controller;

use InoOicServer\Server\ServerInfo;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Json\Json;


class DiscoveryController extends AbstractActionController
{

    /**
     * @var ServerInfo
     */
    protected $serverInfo;


    /**
     * @return ServerInfo
     */
    public function getServerInfo()
    {
        return $this->serverInfo;
    }


    /**
     * @param ServerInfo $serverInfo
     */
    public function setServerInfo(ServerInfo $serverInfo)
    {
        $this->serverInfo = $serverInfo;
    }


    public function indexAction()
    {
        
        /* @var $response \Zend\Http\Response */
        $response = $this->getResponse();
        $response->getHeaders()->addHeaders(array(
            'Content-Type' => 'application/json'
        ));
        
        $issuer = $this->getServerInfo()->getBaseUri();
        $version = $this->getServerInfo()->getServerVersion();
        /*
         * http://openid.net/specs/openid-connect-discovery-1_0.html#ProviderMetadata
         */
        $configuration = Json::encode(
            array(
                'version' => $version,
                'issuer' => $issuer,
                'authorization_endpoint' => $issuer . '/oic/authorize',
                'token_endpoint' => $issuer . '/oic/token',
                'userinfo_endpoint' => $issuer . '/oic/userinfo',
                'end_session_endpoint' => $issuer . '/oic/endsession',
                
                // Required
                'jwks_uri' => '',
                'scopes_supported' => array(
                    'openid'
                ),
                'response_types_supported' => array(
                    'code'
                ),
                'subject_types_supported' => '',
                'id_token_signing_alg_values_supported' => '',
                // ---
                
                'claims_supported' => array(
                    'id',
                    'name',
                    'email',
                    'given_name',
                    'family_name'
                ),
                'request_parameter_supported' => false,
                'request_uri_parameter_supported' => false,
                'service_documentation' => 'https://homeproj.cesnet.cz/projects/shongo/wiki/TestAuthServer'
            ));
        
        $response->setContent($configuration);
        
        return $response;
    }
}