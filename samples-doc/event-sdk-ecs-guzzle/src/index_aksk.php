<?php

// #############################################################################
// sample on how to call ECS API with signed request using:
//  - SecurityAccessKey
//  - SecuritySecretKey
//  - SecurityToken
// received from an agency
// #############################################################################

require_once __DIR__ . '/../vendor/autoload.php';

use OTC\Request as OTCRequest;
use OTC\Signer;
use OTC\fg_timer_event\TimerEvent;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

function handler($event, $context)
{
  $logger = $context->getLogger();

  $timerEvent = new TimerEvent($event);
  $timerName = $timerEvent->getTriggerName();
  $userEvent = $timerEvent->getUserEvent();

  // get project_id and instance_id from environment variables
  $projectId = getenv('RUNTIME_PROJECT_ID');

  // get ecs endpoint from context or use default
  $ecsEndpoint = $context->getUserData('ECS_ENDPOINT') ?: 'ecs.eu-de.otc.t-systems.com';

  $instanceId = $context->getUserData('ECS_INSTANCE_ID');

  $logger->info("Timer $timerName received with user event: $userEvent for ECS instance: $instanceId");

  $url = "https://$ecsEndpoint/v1/$projectId/cloudservers/action";

  $logger->info("calling ECS API: $url");

  if ($userEvent === 'start') {
    $body = json_encode([
      'os-start' => [
        'servers' => [['id' => $instanceId]],
      ],
    ]);
  } elseif ($userEvent === 'stop') {
    $body = json_encode([
      'os-stop' => [
        'type' => 'SOFT',
        'servers' => [['id' => $instanceId]],
      ],
    ]);
  } else {
    $logger->error("Unknown user event: $userEvent");
    return [
      'statusCode' => 400,
      'isBase64Encoded' => false,
      'body' => json_encode(['error' => "Unknown user event: $userEvent"]),
      'headers' => ['Content-Type' => 'application/json'],
    ];
  }

  $headers = [
    'Content-Type' => 'application/json;charset=utf8',
    'Host' => $ecsEndpoint,
    'X-Project-Id' => $projectId,
    // 'X-Content-Sha256' => 'UNSIGNED-PAYLOAD',

  ];

  $req = new OTCRequest('POST', $url, $headers, $body);

  $signer = new Signer();
  $signer->Key = $context->getSecurityAccessKey();
  $signer->Secret = $context->getSecuritySecretKey();
  $signer->SecurityToken = $context->getSecurityToken();

  $signer->Sign($req);

  // Use Guzzle client to make the request with signed headers
  $client = new Client([
    'verify' => false, // Disable SSL verification (use with caution in production)
  ]);

  $headers = [];
  foreach ($signer->curlHeaders($req) as $key => $value) {
    $h=explode(":", $value, 2);
    $headers[$h[0]] = $h[1];
  }

  try {
    $guzzleRequest = new Request(
      'POST',
      $url, 
      $headers,
      $body
    );

    $response = $client->send($guzzleRequest);
    $status = $response->getStatusCode();
    $responseBody = (string) $response->getBody();

    $logger->info("Status Code: $status");
    $logger->info("Response Body: $responseBody");
  } catch (\Exception $e) {
    $logger->error('Error: ' . $e->getMessage());
    $responseBody = $e->getMessage();
    $status = 500;
  }

  return [
    'statusCode' => $status,
    'isBase64Encoded' => false,
    'body' => $responseBody,
    'headers' => ['Content-Type' => 'application/json'],
  ];
}
