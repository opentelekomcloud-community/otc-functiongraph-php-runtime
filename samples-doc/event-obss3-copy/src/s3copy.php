<?php
require __DIR__ . '/../vendor/autoload.php';

use Obs\ObsClient;
use Obs\ObsException;

use OTC\fg_obss3_event\OBSS3Event;

function handler($event, $context)
{
  $logger = $context->getLogger();

  $obsEvent = new OBSS3Event($event);

  $logger->info('OBS Event - Bucket: ' . $obsEvent->getRecords()[0]->getS3()->getBucket()->getName());

  $srcBucket = $obsEvent->getRecords()[0]->getS3()->getBucket()->getName();
  $srcKey = $obsEvent->getRecords()[0]->getS3()->getObject()->getKey();

  $obsEndpoint = getenv('OBS_ENDPOINT') ?: "obs.eu-de.otc.t-systems.com";
  $destBucket = getenv('OUTPUT_BUCKET');
  $destKey = "copy-" . $srcKey;

  $logger->info('Source bucket: ' . $srcBucket);
  $logger->info('Source key: ' . $srcKey);
  $logger->info('Destination bucket: ' . $destBucket);
  $logger->info('Destination key: ' . $destKey);
  $logger->info('OBS Endpoint: ' . $obsEndpoint);

   $statusCode = 200;

  // Infer the image type from the file suffix
  $typeMatch = [];
  if (!preg_match('/\.([^.]*)$/', $srcKey, $typeMatch)) {
    $logger->info("Could not determine the image type.");
    return;
  }
  $imageType = strtolower($typeMatch[1]);
  // Check that the image type is supported
  if ($imageType !== "jpg" && $imageType !== "png") {
    $statusCode = 400;
    $logger->info("Unsupported image type: " . $imageType);
    return;
  }

  $ak = $context->getSecurityAccessKey();
  $sk = $context->getSecuritySecretKey();
  $st = $context->getSecurityToken();

  // Create an instance of ObsClient.
  $obsClient = ObsClient::factory([
    'key' => $ak,
    'secret' => $sk,
    'endpoint' => "https://$obsEndpoint",
    'security_token' => $st,
    'socket_timeout' => 30,
    'connect_timeout' => 10
  ]);

  $ret = [];
 

  try {
    upload($srcBucket, $srcKey, $destBucket, $destKey, $obsClient, $logger);

  } catch (ObsException $obsException) {
    $statusCode = 500;
    printf("ExceptionCode:%s\n", $obsException->getExceptionCode());
    printf("ExceptionMessage:%s\n", $obsException->getExceptionMessage());
  } finally {
    $obsClient->close();
  }

  $output = [
    'statusCode' => $statusCode,
  ];

  return $output;
}

function upload($bucket, $key, $destBucket, $destKey, $obsClient, $logger)
{
  $logger->info('Uploading');
  $resp = $obsClient->getObject([
    'Bucket' => $bucket,
    'Key' => $key,
  ]);

  $obsClient->putObject([
    'Bucket' => $destBucket,
    'Key' => $destKey,
    'Body' => $resp['Body']
  ]);
}