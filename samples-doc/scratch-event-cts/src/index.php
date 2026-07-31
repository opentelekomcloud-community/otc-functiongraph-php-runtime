<?php

require_once __DIR__ . '/../vendor/autoload.php';

use OTC\fg_cts_event\CTSEvent;

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

  $ctsEvent = new CTSEvent($event);

  $logger->info('CTS Event- Trace type: ' . $ctsEvent->getTraceType());
  $logger->info('CTS Event- Service type: ' . $ctsEvent->getServiceType());

  return [
    'service_type' => $ctsEvent->getServiceType(),
    'trace_type' => $ctsEvent->getTraceType(),
    'trace_name' => $ctsEvent->getTraceName(),
  ];
}
