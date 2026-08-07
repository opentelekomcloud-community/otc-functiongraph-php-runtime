<?php

use app\middlewares\FGContainerMiddleware;

use app\controllers\FGController;

use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */

// register a Flight middleware to process the request headers
$router->group('', function (Router $router) use ($app) {

  // FunctionGraph function initializer 
  $router->post('/init', [FGController::class, 'init'])->setAlias('init');

  // FunctionGraph function handler
  $router->post('/invoke', [FGController::class, 'invoke'])->setAlias('invoke');

}, [new FGContainerMiddleware($app)]);
