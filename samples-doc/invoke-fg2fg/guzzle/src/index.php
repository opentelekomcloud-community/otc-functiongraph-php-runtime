<?php

// Sample code to invoke a FunctionGraph function from another FunctionGraph function using AK/SK.
// This example demonstrates how to set up the request, sign it with AK/SK,
// and send it to the target FunctionGraph function defined as CALL_FG_URN in the user data.

require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as HttpRequest;
use OTC\Request as OTCRequest;
use OTC\Signer;

function handler($event, $context)
{

  $logger = $context->getLogger();
  
  $callFgUrn = $context->getUserData('CALL_FG_URN');
  $logger->info('Starting invocation of FunctionGraph function: ' . $callFgUrn);

  $ak = $context->getSecurityAccessKey();
  $sk = $context->getSecuritySecretKey();
  $token = $context->getSecurityToken();

  // get region from the function URN, default to 'eu-de' if not available
  $region = $callFgUrn ? explode(':', $callFgUrn)[2] : 'eu-de';

  $fgEndpoint = "https://functiongraph.{$region}.otc.t-systems.com";

  // get Project ID from environment variable, default to empty string if not available
  $projectId = getenv('RUNTIME_PROJECT_ID') ?: '';
  $invokeUri = $fgEndpoint . '/v2/' . $projectId . '/fgs/functions/' . $callFgUrn . '/invocations';

  $payload = json_encode([
    'key' => 'Hello FunctionGraph',
  ], JSON_THROW_ON_ERROR);

  $headers = [
    'Content-Type' => 'application/json;charset=utf8',
    'Host' => "functiongraph.{$region}.otc.t-systems.com",
    'X-Project-Id' => $projectId,
  ];

  $request = new OTCRequest('POST', $invokeUri, $headers, $payload);

  $signer = new Signer();
  $signer->Key = $ak;
  $signer->Secret = $sk;
  $signer->SecurityToken = $token;
  $signer->Sign($request);

  $client = new Client([
    'verify' => false,
    'timeout' => 30,
  ]);

  $signedHeaders = [];
  foreach ($signer->curlHeaders($request) as $headerValue) {
    [$headerName, $headerContent] = explode(':', $headerValue, 2);
    $signedHeaders[trim($headerName)] = trim($headerContent);
  }

  $httpRequest = new HttpRequest('POST', $invokeUri, $signedHeaders, $payload);
  $response = $client->send($httpRequest);
  $responseBody = (string) $response->getBody();

  $logger->info('Response: ' . $responseBody);
  $logger->info('Response status code: ' . $response->getStatusCode());
  
  if ($response->getStatusCode() >= 400) {
    throw new RuntimeException(
      'Backend request failed with status ' . $response->getStatusCode() . ': ' . $responseBody
    );
  }

  return $responseBody;
}
