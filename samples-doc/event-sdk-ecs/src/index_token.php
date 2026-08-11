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

  $headers = array(
    'Content-Type: application/json;charset=utf8',
    "x-auth-token: $token"
  );

  $curl = curl_init();


  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $body,
    CURLOPT_HEADER => true,
    CURLOPT_HTTPHEADER => $headers,
  ));

  $response = curl_exec($curl);
  $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);

  if ($status == 0) {
    $logger->error('Error: ' . curl_error($curl));
    $responseBody = curl_error($curl);
    $status = 500;
  } else {
    $responseBody = substr($response, $headerSize);
    $logger->info("Status Code: $status");
    $logger->info("Response Body: $responseBody");
  }

  curl_close($curl);

  return [
    'statusCode' => $status,
    'isBase64Encoded' => false,
    'body' => $responseBody,
    'headers' => ['Content-Type' => 'application/json'],
  ];
}
