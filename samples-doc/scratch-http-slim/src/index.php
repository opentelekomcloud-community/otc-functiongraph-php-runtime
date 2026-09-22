<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/loggingmiddleware.php';

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\AppFactory;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Stream;

// Function to retrieve FunctionGraph environment variable values
// from the RUNTIME_USERDATA environment variable.
// $name is the key of the FunctionGraph environment variable to retrieve.
function getUserData($name)
{
  $userdata = getenv('RUNTIME_USERDATA');
  if ($userdata === false) {
    return null;
  }
  $runtime_userdata = json_decode($userdata, true);
  return $runtime_userdata[$name] ?? null;
}

$app = AppFactory::create();
$app->addRoutingMiddleware();
$app->add(new LoggingMiddleware());
$errorMiddleware = $app->addErrorMiddleware(false, true, true);

$errorMiddleware->setErrorHandler(HttpNotFoundException::class, static function (ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails, bool $logErrors, bool $logErrorDetails) use ($app): ResponseInterface {
  $response = $app->getResponseFactory()->createResponse(404);
  $response->getBody()->write((string) json_encode(['message' => 'Not found!'], JSON_THROW_ON_ERROR));

  return $response->withHeader('Content-Type', 'application/json');
});


$app->get('/', static function (ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
  $message = 'Hello World of FunctionGraph - this is a sample application using Slim Framework!';
  $response->getBody()->write($message);
  return $response;
});

// Endpoint to handle favicon requests
$app->get('/favicon.ico', static function (ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {

  $logger = $request->getAttribute('logger');
  $message = 'GET Favicon called';

  $logger->error($message);
  // 1. Path to your favicon file
  $faviconPath = __DIR__ . '/favicon.ico';

  $logger->info("Favicon path: " . $faviconPath);
  // 2. Check if the file exists to avoid crashes
  if (!file_exists($faviconPath)) {
    return $response->withStatus(404);
  }

  // 3. Create a stream and attach it to the response
  $stream = new Stream(fopen($faviconPath, 'r'));

  return $response
    ->withHeader('Content-Type', 'image/x-icon')
    ->withBody($stream);
});

// Endpoint to handle search requests with optional name query parameter
$app->get('/search', static function (ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
  $name = $request->getQueryParams()['name'] ?? '';
  $message = $name === '' ? 'you searched for nothing, specify a name with ?name=yourname' : 'you searched for: ' . $name;

  $response->getBody()->write($message);
  return $response;
});

// Endpoint to return all request headers as JSON
$app->get('/headers', static function (ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
  $headers = $request->getHeaders();
  $response->getBody()->write((string) json_encode($headers, JSON_THROW_ON_ERROR));
  return $response->withHeader('Content-Type', 'application/json');
});

// Endpoint to handle user requests with optional name parameter
$app->map(['GET', 'POST'], '/user[/{name}]', static function (ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
  $name = $args['name'] ?? '';
  $response->getBody()->write($name === '' ? 'hello unknown' : 'hello ' . $name);
  return $response;
});

// Endpoint to return a JSON response with sample data
$app->get('/json', static function (ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
  $logger = $request->getAttribute('logger');

  $logger->info('Handling /json request');
  $logger->debug('sample on how to use DEBUG logging');
  $logger->info('sample on how to use INFO logging');
  $logger->error('sample on how to use ERROR logging');

  $response->getBody()->write((string) json_encode([
    'hello' => 'world',
    'project_id' => $request->getAttribute('cffProjectId'),
    'AK' => $request->getAttribute('cffAccessKey'),
    'MY_USER_DATA' => getUserData('MY_USER_DATA'),
    'MY_ENCRYPTED_USER_DATA' => getUserData('MY_ENCRYPTED_USER_DATA')
  ], JSON_THROW_ON_ERROR));

  return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
