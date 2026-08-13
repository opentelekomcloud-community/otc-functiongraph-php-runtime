<?php

// #############################################################################
// sample on how to call ECS API with signed request using:
//  - SecurityAccessKey
//  - SecuritySecretKey
//  - SecurityToken
// received from an agency
// additionally this sample shows how to use FunctionGraph dependencies 
// together with project dependencies. See README.md for details.
// #############################################################################

// include project dependencies
require_once __DIR__ . '/../dependencies/autoload.php';


// include all PHP files in vendor directory of FunctionGraph
// except composer and autoload.php
$path = __DIR__ . '/../vendor';
$dir      = new RecursiveDirectoryIterator($path);
$iterator = new RecursiveIteratorIterator($dir);
foreach ($iterator as $file) {
    $fname = $file->getFilename();
  if (preg_match('%\.php$%', $fname)
    && strpos($file->getPathname(), '/composer/') === false
    && strpos($file->getPathname(), '/autoload.php') === false
  ) {
        require_once $file->getPathname();
    }
}

use OTC\Signer;
use OTC\Request;
use OTC\fg_timer_event\TimerEvent;

use Brick\DateTime\LocalDate;
use Brick\DateTime\TimeZone;


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
  ];

  $req = new Request('POST', $url, $headers, $body);

  $signer = new Signer();
  $signer->Key = $context->getSecurityAccessKey();
  $signer->Secret = $context->getSecuritySecretKey();
  $signer->SecurityToken = $context->getSecurityToken();

  $curl = $signer->Sign($req);

  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_HEADER, true);

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
    'localDate' => LocalDate::now(TimeZone::utc()),
    'headers' => ['Content-Type' => 'application/json'],
  ];
}
