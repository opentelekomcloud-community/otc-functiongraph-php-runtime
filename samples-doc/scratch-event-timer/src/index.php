<?php

require_once __DIR__ . '/../vendor/autoload.php';

use OTC\fg_timer_event\TimerEvent;


function initializer($context)
{
  $logger = $context->getLogger();
  $output = 'Initializer called for function: ' . $context->getFunctionName();
  $logger->debug($output);
  return $output;
}


function handler($event, $context)
{
  $logger = $context->getLogger();

  $logger->info('Function name: ' . $context->getFunctionName());

  $timerEvent = new TimerEvent($event);
  $logger->info('Trigger name from event: ' . $timerEvent->getTriggerName());

  $output = [
    'statusCode' => 200,
    'headers' => [
      'Content-Type' => 'application/json',
    ],
    'isBase64Encoded' => false,
    'body' => json_encode($event),
  ];

  return $output;
}
