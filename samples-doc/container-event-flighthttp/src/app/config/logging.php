<?php

use Monolog\Formatter\LineFormatter;

use app\processors\RequestHeaderProcessor;

// register a logger service
$app->register('log', Monolog\Logger::class, ['flighthttp'], function($log) {
    
    $dateFormat = "Y-m-d\TH:i:s.v\Z";
    
    $output = "[%datetime%] [%level_name%] [%extra.X_CFF_REQUEST_ID%] %message% %context%\n";
    $formatter = new LineFormatter($output, $dateFormat);

    $runtimeLogLevel = strtoupper((string) getenv('RUNTIME_LOG_LEVEL'));
    $levelMap = [
      'DEBUG' => Monolog\Logger::DEBUG,
      'INFO' => Monolog\Logger::INFO,
      'WARNING' => Monolog\Logger::WARNING,
      'ERROR' => Monolog\Logger::ERROR,
    ];
    $monologLevel = $levelMap[$runtimeLogLevel] ?? Monolog\Logger::DEBUG;

    $stream = new Monolog\Handler\StreamHandler('php://stdout', $monologLevel);
    
    // set the formatter for the stream handler
    $stream->setFormatter($formatter);
    // configure the logger to use the stream handler 
    $log->pushHandler($stream);
    // and the request header processor
    $log->pushProcessor(new RequestHeaderProcessor());
});
