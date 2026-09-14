<?php

// Sample code to invoke a FunctionGraph function from another FunctionGraph function
// using "Token" from an agency and send it to the target FunctionGraph function
// defined as CALL_FG_URN in the user data.
// This example uses Guzzle for the HTTP request.

require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as HttpRequest;

function handler($event, $context)
{

  $logger = $context->getLogger();

  $callFgUrn = $context->getUserData('CALL_FG_URN');
  $logger->info('Starting invocation of FunctionGraph function: ' . $callFgUrn);

  $token = $context->getToken();

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
    'X-Auth-Token' => $token,
  ];

  $client = new Client([
    'verify' => false,
    'timeout' => 30,
  ]);

  $httpRequest = new HttpRequest('POST', $invokeUri, $headers, $payload);
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
