<?php

class SampleEvent
{
  private array $event;

  public function __construct($event)
  {
    $array = json_decode(json_encode($event), true);
    $this->event = $array;
  }

  public function getKey(): string
  {
    return $this->event['key'] ?? '';
  }
}


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

  $sampleEvent = new SampleEvent($event);
  $logger->info('Key value from event: ' . $sampleEvent->getKey());

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
