<?php

require_once __DIR__ . '/../vendor/autoload.php';

use OTC\fg_apig_event\APIGEvent;
use OTC\fg_apig_event\APIGResponse;

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

  $apigEvent = new APIGEvent($event);
  $isBase64Encoded = $apigEvent->isBase64Encoded();

  $body = $apigEvent->getBody();
  $logger->info('APIG Event body: ' . $body);

  $responseType = $apigEvent->getQueryStringParameter('responseType');

  if ($responseType === 'html') {
    $output = new APIGResponse(200, '', ['Content-Type' => 'text/html; charset=utf-8']);
    $output->setBody('<html><h1>Welcome to use FunctionGraph</h1></html>', $isBase64Encoded);
  } elseif ($responseType === 'json') {
    $output = new APIGResponse(200, '', ['Content-Type' => 'application/json']);
    $cleaned = str_replace('\\"', '"', $body ?? '');
    $parsed = json_decode($cleaned, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
      $output->setBody(['error' => 'Invalid JSON input'], $isBase64Encoded);
    } else {
      $output->setBody($parsed, $isBase64Encoded);
    }
  } else {
    $output = new APIGResponse(200, '', ['Content-Type' => 'text/html; charset=utf-8']);
    $output->setBody(
      '<html>Please construct the url with query parameters responseType=html, responseType=json</html>',
      $isBase64Encoded,
    );
  }

  $logger->info('returning: ' . json_encode($output->toJson()));

  return $output->toJson();
}
