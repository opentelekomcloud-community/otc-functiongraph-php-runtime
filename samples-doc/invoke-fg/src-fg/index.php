<?php

function handler($event, $context) {
    $logger = $context->getLogger();
    $logger->info("Function name: " . $context->getFunctionName());

    return [
        "statusCode" => 200,
        "isBase64Encoded" => false,
        "body" => json_encode($event),
        "headers" => [
            "Content-Type" => "application/json"
        ]
    ];
}