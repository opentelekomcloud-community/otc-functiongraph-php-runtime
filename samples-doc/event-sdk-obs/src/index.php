<?php
require __DIR__ . '/../vendor/autoload.php';

use Obs\ObsClient;
use Obs\ObsException;


function handler($event, $context)
{
  $logger = $context->getLogger();

  $ak = $context->getSecurityAccessKey();
  $sk = $context->getSecuritySecretKey();
  $st = $context->getSecurityToken();

  $endpoint = getenv('OBS_ENDPOINT_URL') ?: "obs.eu-de.otc.t-systems.com";

  $obsClient = ObsClient::factory([
    'key' => $ak,
    'secret' => $sk,
    'endpoint' => "https://$endpoint",
    'security_token' => $st,
    'socket_timeout' => 30,
    'connect_timeout' => 10
  ]);

  $ret = [];
  $statusCode = 200;

  try {
    $resp = $obsClient->listBuckets([
      'QueryLocation' => true
    ]);

    foreach ($resp['Buckets'] as $index => $bucket) {
      $ret[] = [
        'Name' => $bucket['Name'],
        'CreationDate' => $bucket['CreationDate'],
        'Location' => $bucket['Location'],
      ];
    }
    
  } catch (ObsException $obsException) {
    $statusCode = 500;
    printf("ExceptionCode:%s\n", $obsException->getExceptionCode());
    printf("ExceptionMessage:%s\n", $obsException->getExceptionMessage());
  }


  $output = [
    'statusCode' => $statusCode,
    'headers' => [
      'Content-Type' => 'application/json',
    ],
    'isBase64Encoded' => false,
    'body' => json_encode($ret),
  ];

  return $output;
}
