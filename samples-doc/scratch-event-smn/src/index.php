<?php

require_once __DIR__ . '/../vendor/autoload.php';

use OTC\fg_smn_event\SMNEvent;

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

  $smnEvent = new SMNEvent($event);

  $logger->info('SMN Function name: ' . $smnEvent->getFunctionName());
  $logger->info('SubscriptionURN: ' . $smnEvent->getRecords()[0]->getEventSubscriptionUrn());

  $output = [
    'function_name'    => $smnEvent->getFunctionName(),
    'subscription_urn' => $smnEvent->getRecords()[0]->getEventSubscriptionUrn(),
  ];

  return $output;
}
