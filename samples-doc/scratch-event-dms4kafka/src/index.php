<?php

require_once __DIR__ . '/../vendor/autoload.php';

use OTC\fg_dms4kafka_event\DMS4KafkaEvent;

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

  $logger->info('Function Name: ' . $context->getFunctionName());

  $dms4kafkaEvent = new DMS4KafkaEvent($event);

  $logger->info('Trigger type: ' . $dms4kafkaEvent->getTriggerType());

  $output = [
    'trigger_type' => $dms4kafkaEvent->getTriggerType(),
  ];

  return $output;
}
