<?php

// Function to retrieve FunctionGraph environment variable values
// from the RUNTIME_USERDATA environment variable.
// $name is the key of the FunctionGraph environment variable to retrieve.
function getUserData($name) {
  $runtime_userdata = json_decode(getenv('RUNTIME_USERDATA'), true);
  return $runtime_userdata[$name] ?? null;
}

// Parse the request path from the URL.
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

$headers = getallheaders();
$request_id = $headers['X-Cff-Request-Id'] ?? null;

error_log("Request ID: " . $request_id, 4);

// Handle GET requests to the root path.
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $path === '/') {
  header('Content-Type: application/json');

  $name = $_GET['name'] ?? 'World';
  if (is_array($name)) {
    $name = reset($name);
  }

  echo json_encode([
    'message' => "Hello, {$name}!",
    'user_data' => getUserData('USER_DATA'),
    'secret_user_data' => getUserData('SECRET_USER_DATA'),
  ], JSON_THROW_ON_ERROR);
  exit;
}

// Handle POST requests to the root path.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $path === '/') {
  echo file_get_contents('php://input');
  exit;
}

// Handle requests to paths that are not found.
http_response_code(404);

// Respond with a 404 status code and appropriate message for not found paths.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  header('Content-Type: application/json');
  echo json_encode(['message' => 'Not found!'], JSON_THROW_ON_ERROR);
} else {
  echo 'Not Found';
}
