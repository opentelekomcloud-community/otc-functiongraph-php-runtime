<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/loggingmiddleware.php';


// Function to retrieve FunctionGraph environment variable values
// from the RUNTIME_USERDATA environment variable.
// $name is the key of the FunctionGraph environment variable to retrieve.
function getUserData($name) {
  $userdata = getenv('RUNTIME_USERDATA');
  if ($userdata === false) {
    return null;
  }
  $runtime_userdata = json_decode($userdata, true);
  return $runtime_userdata[$name] ?? null;
}

Flight::group('', function () {

  // root handler
  Flight::route('/', function () {
    echo 'Hello World of FunctionGraph - this is a sample application using FlightPHP!';
  });

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

    $logger->debug('sample on how to use DEBUG logging');
    $logger->info('sample on how to use INFO logging');
    $logger->error('sample on how to use ERROR logging');

    Flight::json([
      'hello' => 'world',
      'AK' => $ak,
      'MY_USER_DATA' => getUserData('MY_USER_DATA'),
      'MY_ENCRYPTED_USER_DATA' => getUserData('MY_ENCRYPTED_USER_DATA'),
    ]);

  })->setAlias('json');

}, [LoggingMiddleware::class]);


Flight::start();
