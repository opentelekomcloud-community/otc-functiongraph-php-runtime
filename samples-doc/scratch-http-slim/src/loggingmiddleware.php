<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestLogger
{
  public function __construct(
    private readonly string $requestId,
    private readonly string $logLevel,
  ) {}

  public function log(mixed ...$args): void
  {
    $this->write('LOG', $args);
  }

  public function debug(mixed ...$args): void
  {
    if ($this->isEnabled('DEBUG')) {
      $this->write('DEBUG', $args);
    }
  }

  public function info(mixed ...$args): void
  {
    if ($this->isEnabled('INFO')) {
      $this->write('INFO', $args);
    }
  }

  public function warn(mixed ...$args): void
  {
    if ($this->isEnabled('WARN')) {
      $this->write('WARN', $args);
    }
  }

  public function error(mixed ...$args): void
  {
    if ($this->isEnabled('ERROR')) {
      $this->write('ERROR', $args);
    }
  }

  private function isEnabled(string $level): bool
  {
    $order = [
      'DEBUG' => 10,
      'INFO' => 20,
      'WARN' => 30,
      'ERROR' => 40,
    ];

    return ($order[$level] ?? 10) >= ($order[$this->logLevel] ?? 10);
  }

  private function write(string $level, array $args): void
  {
    $parts = array_map(static function (mixed $value): string {
      if (is_scalar($value) || $value === null) {
        return (string) $value;
      }

      $encoded = json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
      return $encoded === false ? '[unserializable]' : $encoded;
    }, $args);

    error_log(sprintf('%s [%s] [%s] %s', self::timestamp(), $level, $this->requestId, implode(' ', $parts)));
  }

  private static function timestamp(): string
  {
    $micro = microtime(true);
    $milliseconds = sprintf('%03d', (int) (($micro - floor($micro)) * 1000));

    return gmdate('Y-m-d\\TH:i:s', (int) $micro) . '.' . $milliseconds . 'Z';
  }
}

class LoggingMiddleware implements MiddlewareInterface
{
  public function __construct() {}

  public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
  {
    $requestId = $request->getHeaderLine('X-Cff-Request-Id') ?: 'no-request-id';
    $projectId = $request->getHeaderLine('X-Cff-Project-Id') ?: 'no-project-id';
    $region = $request->getHeaderLine('X-Cff-Region') ?: 'no-region';
    $authToken = $request->getHeaderLine('X-CFF-Auth-Token') ?: 'no agency/include keys';
    $accessKey = $request->getHeaderLine('X-CFF-Security-Access-Key') ?: 'no agency/include keys';
    $secretKey = $request->getHeaderLine('X-CFF-Security-Secret-Key') ?: 'no agency/include keys';
    $securityToken = $request->getHeaderLine('X-CFF-Security-Token') ?: 'no agency/include keys';
    $logLevel = strtoupper((string) getenv('RUNTIME_LOG_LEVEL')) ?: 'DEBUG';
    $logger = new RequestLogger($requestId, $logLevel);

    $request = $request
      ->withAttribute('logger', $logger)
      ->withAttribute('cffRequestId', $requestId)
      ->withAttribute('cffProjectId', $projectId)
      ->withAttribute('cffAuthToken', $authToken)
      ->withAttribute('cffAccessKey', $accessKey)
      ->withAttribute('cffSecretKey', $secretKey)
      ->withAttribute('cffSecurityToken', $securityToken)
      ->withAttribute('cffRegion', $region);

    $logger->debug('Request started', $request->getMethod(), (string) $request->getUri());
    $response = $handler->handle($request);

    foreach (
      [
        'X-Cff-Request-Id' => $requestId,
        'X-Cff-Project-Id' => $projectId,
        'X-Cff-Region' => $region,
        'X-CFF-Auth-Token' => $authToken,
        'X-CFF-Security-Access-Key' => $accessKey,
        'X-CFF-Security-Secret-Key' => $secretKey,
        'X-CFF-Security-Token' => $securityToken,
      ] as $header => $value
    ) {
      if ($value !== 'no agency/include keys' && $value !== 'no-request-id') {
        $response = $response->withHeader($header, $value);
      }
    }

    return $response;
  }
}
