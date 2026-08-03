<?php

// if installed with composer
require '../vendor/autoload.php';
// or if installed manually by zip file
// require 'flight/Flight.php';
require __DIR__ . '/loggingmiddleware.php';


Flight::group('', function () {


  //  Deliver favicon.ico with 204 No Content to avoid unnecessary 404 errors in logs.
  Flight::route('/favicon.ico', function () {
    Flight::response()->status(204);
    Flight::response()->send();

  });

  // example with query parameter 
  Flight::route('/search', function () {
    $name = Flight::request()->query->name ?? '';
    if ($name == '') {
      echo 'you searched for nothing, specify a name with ?name=yourname';
    } else {
      echo 'you searched for: ' . $name;
    }
  });

  // example with optional path parameter 
  Flight::route('/user(/@name)', function (?string $name) {

    if ($name == '') {
      echo 'hello unknown';
    } else {
      echo 'hello ' . $name;
    }
  })->setAlias('user');

  // Example route that returns JSON data
  Flight::route('/json', function () {

    $logger = Flight::get('logger');
    $logger->info('Handling /json request');

    $ak = Flight::get('cffAccessKey');

    $logger->debug('DEBUG');
    $logger->info('INFO');
    $logger->error('ERROR');

    Flight::json([
      'hello' => 'world',
      'AK' => $ak
    ]);

  })->setAlias('json');

}, [LoggingMiddleware::class]);


Flight::start();
