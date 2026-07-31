<?php

/**
 * Sample OBS S3 event handler for FunctionGraph.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use OTC\fg_obss3_event\OBSS3Event;

function initializer($context)
{
  $logger = $context->getLogger();
  $output = 'Initializer called for function: ' . $context->getFunctionName();
  $logger->debug($output);
  return $output;
}

/**
 * Main handler function for the OBS S3 event.
 *
 * @param mixed $event   The OBS S3 event data
 * @param mixed $context FunctionGraph execution context
 * @return array Response with event name and bucket name
 */
function handler($event, $context)
{
  $logger = $context->getLogger();

  $logger->info('Function Name: ' . $context->getFunctionName());

  $obsEvent = new OBSS3Event($event);

  $logger->info('OBS Event: ' . $obsEvent->getRecords()[0]->getEventName());
  $logger->info('OBS Event - Bucket: ' . $obsEvent->getRecords()[0]->getS3()->getBucket()->getName());

  $output = [
    'event_name' => $obsEvent->getRecords()[0]->getEventName(),
    'bucketname' => $obsEvent->getRecords()[0]->getS3()->getBucket()->getName(),
  ];

  return $output;
}
