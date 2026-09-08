<?php

require __DIR__ . '/../dependencies/autoload.php';

use Brick\DateTime\LocalDate;
use Brick\DateTime\TimeZone;

function initializer($context) {
    $logger = $context->getLogger();
    $logger->info("Function name: " . $context->getFunctionName());

    return;
}

function handler($event, $context) {
    $logger = $context->getLogger();
    $logger->info("Function name: " . $context->getFunctionName());

    $logger->info('Local date: ' . LocalDate::now(TimeZone::utc()));

    return [
        "statusCode" => 200,
        "isBase64Encoded" => false,
        "body" => json_encode($event),
        "headers" => [
            "Content-Type" => "application/json"
        ]
    ];
}