<?php

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $path === '/') {
	header('Content-Type: application/json');

	$name = $_GET['name'] ?? 'World';
	if (is_array($name)) {
		$name = reset($name);
	}

	echo json_encode(['message' => "Hello, {$name}!"], JSON_THROW_ON_ERROR);
	exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $path === '/') {
	echo file_get_contents('php://input');
	exit;
}

http_response_code(404);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	header('Content-Type: application/json');
	echo json_encode(['message' => 'Not found!'], JSON_THROW_ON_ERROR);
} else {
	echo 'Not Found';
}
