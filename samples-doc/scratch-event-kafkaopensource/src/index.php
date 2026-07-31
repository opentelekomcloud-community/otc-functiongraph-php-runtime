<?php

require_once __DIR__ . '/../vendor/autoload.php';

use OTC\fg_kafkaopensource_event\KafkaOpenSourceEvent;

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

  $kafkaOpenSourceEvent = new KafkaOpenSourceEvent($event);

  $logger->info('Trigger type: ' . $kafkaOpenSourceEvent->getTriggerType());

  $output = [
    'trigger_type' => $kafkaOpenSourceEvent->getTriggerType(),
  ];

  return $output;
}
