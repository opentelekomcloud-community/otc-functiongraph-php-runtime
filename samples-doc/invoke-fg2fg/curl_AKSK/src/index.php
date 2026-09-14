<?php

// Sample code to invoke a FunctionGraph function from another FunctionGraph function
// using "AK/SK" from an agency and send it to the target FunctionGraph function
// defined as CALL_FG_URN in the user data.
// This example uses native PHP cURL for the HTTP request.

require __DIR__ . '/../vendor/autoload.php';

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

  $curlHandle = null;

  try {
    $curlHandle = curl_init($invokeUri);
    if ($curlHandle === false) {
      throw new RuntimeException('Unable to initialize cURL');
    }

    curl_setopt_array($curlHandle, [
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_HTTPHEADER => $signer->curlHeaders($request),
      CURLOPT_POSTFIELDS => $payload,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_TIMEOUT => 30,
    ]);

    $responseBody = curl_exec($curlHandle);
    if ($responseBody === false) {
      throw new RuntimeException(curl_error($curlHandle));
    }

    $status = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
    curl_close($curlHandle);
    $curlHandle = null;

    $logger->info('Response: ' . $responseBody);
    $logger->info('Response status code: ' . $status);

    if ($status >= 400) {
      throw new RuntimeException(
        'Backend request failed with status ' . $status . ': ' . $responseBody
      );
    }

    return $responseBody;
  } finally {
    if ($curlHandle !== null) {
      curl_close($curlHandle);
    }
  }
}