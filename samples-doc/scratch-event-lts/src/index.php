<?php

require_once __DIR__ . '/../vendor/autoload.php';

use OTC\fg_lts_event\LTSEvent;


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

  $ltsEvent = new LTSEvent($event);
  $logger->info('Logs: ' . json_encode($ltsEvent->getLogs()));

  $output = [
    'logs' => $ltsEvent->getLogs(),
  ];


  return $output;
}
