<?php

namespace InoOicServerTest\Oic\Client\Authentication;

use Zend\Http;
use InoOicServer\Oic\Client\Authentication\CredentialsExtractor;
use InoOicServer\Oic\Client\Authentication\Authentication;


class CredentialsExtractorTest extends \PHPUnit_Framework_TestCase
{


    public function testExtractClientSecretPost()
    {
        $clientId = 'testclient';
        $clientSecret = 'testsecret';
        
        $httpRequest = new Http\Request();
        $httpRequest->getPost()->fromArray(array(
            'client_id' => $clientId,
            'client_secret' => $clientSecret
        ));
        
        $extractor = new CredentialsExtractor();
        $credentials = $extractor->extract($httpRequest);
        
        $this->assertSame($clientId, $credentials->getClientId());
        $this->assertSame($clientSecret, $credentials->getClientSecret());
        $this->assertSame(Authentication::TYPE_CLIENT_SECRET_POST, $credentials->getType());
    }


    public function testExtractClientSecretBasic()
    {
        $clientId = 'testclient';
        $clientSecret = 'testsecret';
        $authString = base64_encode(sprintf("%s:%s", $clientId, $clientSecret));
        
        $httpRequest = new Http\Request();
        $httpRequest->getHeaders()->addHeaderLine('Authorization', 'Basic ' . $authString);
        
        $extractor = new CredentialsExtractor();
        $credentials = $extractor->extract($httpRequest);
        
        $this->assertSame($clientId, $credentials->getClientId());
        $this->assertSame($clientSecret, $credentials->getClientSecret());
        $this->assertSame(Authentication::TYPE_CLIENT_SECRET_BASIC, $credentials->getType());
    }


    public function testExtractClientSecretBasicWithInvalidHeader()
    {
        $this->setExpectedException('InoOicServer\Oic\Client\Authentication\Exception\CredentialsExtractionException', 'Invalid authorization header format');
        
        $httpRequest = new Http\Request();
        $httpRequest->getHeaders()->addHeaderLine('Authorization', 'invalid_value');
        
        $extractor = new CredentialsExtractor();
        $credentials = $extractor->extract($httpRequest);
    }


    public function testExtractClientSecretBasicWithInvalidAuthType()
    {
        $this->setExpectedException('InoOicServer\Oic\Client\Authentication\Exception\CredentialsExtractionException', 'Invalid authentication type');
        
        $httpRequest = new Http\Request();
        $httpRequest->getHeaders()->addHeaderLine('Authorization', 'invalid_auth xxx');
        
        $extractor = new CredentialsExtractor();
        $credentials = $extractor->extract($httpRequest);
    }


    public function testExtractClientSecretBasicWithInvalidCredentials()
    {
        $this->setExpectedException('InoOicServer\Oic\Client\Authentication\Exception\CredentialsExtractionException', 'Invalid basic authentication credentials');
        
        $httpRequest = new Http\Request();
        $httpRequest->getHeaders()->addHeaderLine('Authorization', 'Basic somestring');
        
        $extractor = new CredentialsExtractor();
        $credentials = $extractor->extract($httpRequest);
    }
}