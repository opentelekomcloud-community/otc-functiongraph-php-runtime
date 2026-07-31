<?php

require_once __DIR__ . '/../vendor/autoload.php';

use OTC\fg_dms4rocketmq_event\DMS4RocketMQEvent;

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

  $dms4rocketmqEvent = new DMS4RocketMQEvent($event);

  $logger->info('Trigger type: ' . $dms4rocketmqEvent->getTriggerType());

  $output = [
    'trigger_type' => $dms4rocketmqEvent->getTriggerType(),
  ];

  return $output;
}
