<?php
use Zend\Http\Client;

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/client_common.php';

$clientId = 'test-console-client-dummy';

$client = _getClient();

/*
 * Authorize request
 */

$request = new \Zend\Http\Request();
$request->setUri($authUri);
$request->getQuery()
    ->fromArray(array(
    'client_id' => $clientId, 
    'response_type' => 'code', 
    'scope' => 'openid', 
    'redirect_uri' => 'https://dummy', 
    'state' => uniqid(), 
    'prompt' => 'login'
));

_dumpRequest($request);
$response = $client->send($request);
_dumpResponse($response);

if ($response->getStatusCode() != 302) {
    exit();
}

$location = $response->getHeaders()
    ->get('Location');
if (! $location) {
    printf("No location header\n");
    exit();
}

$data = $location->uri()
    ->getQueryAsArray();
if (! isset($data['code'])) {
    die("No code in response\n");
}

/*
 * Token request
 */

$client->setMethod('POST');

$request = new \Zend\Http\Request();
$request->setUri($tokenUri);
$request->setMethod('POST');

$request->getPost()
    ->fromArray(array(
    'grant_type' => 'authorization_code', 
    'code' => $data['code'], 
    'redirect_uri' => 'https://dummy', 
    'client_id' => $clientId
));

_dumpRequest($request);
$client->setMethod('POST');
$response = $client->send($request);
_dumpResponse($response);

$tokenData = \Zend\Json\Json::decode($response->getContent(), \Zend\Json\Json::TYPE_ARRAY);
_dump($tokenData);
if (isset($tokenData['error'])) {
    die("ERROR\n");
}
/*
 * User info request
 */

$request = new \Zend\Http\Request();
$request->setUri($userInfoUri);
$request->getQuery()
    ->fromArray(array(
    'schema' => 'openid'
));
$request->getHeaders()
    ->addHeaders(array(
    'Authorization' => sprintf("Bearer %s", $tokenData['access_token'])
));

_dumpRequest($request);
$client->setMethod('GET');
$response = $client->send($request);
_dumpResponse($response);

$userData = \Zend\Json\Json::decode($response->getContent(), \Zend\Json\Json::TYPE_ARRAY);
_dump($userData);
