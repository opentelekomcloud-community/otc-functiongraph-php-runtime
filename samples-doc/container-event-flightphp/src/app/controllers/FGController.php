<?php

namespace app\controllers;

use app\middlewares\CFFHeader;
use Monolog\Logger;
use flight\Engine;

use OTC\fg_timer_event\TimerEvent;

class FGController
{
  protected Engine $app;
  protected Logger $logger;
  

  public function __construct($app)
  {
    $this->app = $app;
    $this->logger = $app->log();
  }

  // FunctionGraph function initializer
  public function init()
  {
    $this->logger->debug('init called');

    // return a simple OK response
    $this->app->response()->status(200);
    $this->app->response()->write("OK");
  }

  // FunctionGraph function handler
  public function invoke()
  {
    $this->logger->debug('invoke called');

    $request = $this->app->request();

    $event = json_decode($request->getBody(), true);
    $timerEvent = new TimerEvent($event);

    $triggerName = $timerEvent->getTriggerName();
    $triggerTime = $timerEvent->getTime();
    $triggerUserEvent = $timerEvent->getUserEvent();

    $this->logger->info('Trigger name from event: ' . $timerEvent->getTriggerName());


    // return a JSON response
    $this->app->json([
      'hello' => 'world',
      'trigger' => [
        'name' => $triggerName,
        'time' => $triggerTime,
        'userEvent' => $triggerUserEvent
      ],      
      CFFHeader::REQUEST_ID->value => $this->app->get(CFFHeader::REQUEST_ID->value),
      CFFHeader::FUNC_NAME->value => $this->app->get(CFFHeader::FUNC_NAME->value),
      CFFHeader::REGION->value => $this->app->get(CFFHeader::REGION->value)
    ]);
  }

}