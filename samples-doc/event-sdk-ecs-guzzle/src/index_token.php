<?php

// #####################################################################################
// Sample on how to call ECS API with
// - Token
// received from an agency.
//
// WARNING:
// CURRENTLY NOT WORKING DUE TO A BUG in getToken() function of the FunctionGraph SDK.
//  
// #####################################################################################

require_once __DIR__ . '/../vendor/autoload.php';

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

  $token = $context->getToken();
  $client = new Client();
  $headers = [
    'x-auth-token' => $token,
    'Content-Type' => 'application/json;charset=UTF-8'
  ];

  $request = new Request('POST', $url, $headers, $body);
  $res = $client->sendAsync($request)->wait();
  $responseBody = (string) $res->getBody();
  $status = $res->getStatusCode();


  return [
    'statusCode' => $status,
    'isBase64Encoded' => false,
    'body' => $responseBody,
    'headers' => ['Content-Type' => 'application/json'],
  ];
}
