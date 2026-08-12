<?php

require __DIR__ . '/../../../vendor/autoload.php';

// connect any static calls to the same $app object
$app = Flight::app();

/*
 * Load the config file
 * P.S. When you require a php file and that file returns an array, the array
 * will be returned by the require statement where you can assign it to a var.
 */
$config = require('config.php');

// configure the logger service with Flight
require('logging.php');

// define the router
$router = $app->router();

// load the routes file which contains the functiongraph function initializer and handler
require('routes.php');


Flight::start();
