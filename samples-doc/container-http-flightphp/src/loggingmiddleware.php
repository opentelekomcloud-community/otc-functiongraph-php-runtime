<?php

use flight\Engine;

class RequestLogger
{
  private string $requestId;
  private string $logLevel;

  public function __construct(string $requestId, string $logLevel)
  {
    $this->requestId = $requestId;
    $this->logLevel = $logLevel;
  }

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

    $current = $order[$this->logLevel] ?? $order['DEBUG'];
    $target = $order[$level] ?? $order['DEBUG'];

    return $target >= $current;
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

    $message = implode(' ', $parts);
    error_log(sprintf('%s [%s] [%s] %s', self::timestamp(), $level, $this->requestId, $message));
  }

  private static function timestamp(): string
  {
    $micro = microtime(true);
    $milliseconds = sprintf('%03d', (int) (($micro - floor($micro)) * 1000));

    return gmdate('Y-m-d\\TH:i:s', (int) $micro) . '.' . $milliseconds . 'Z';
  }
}

class LoggingMiddleware
{
  protected Engine $app;

  public function __construct(Engine $app)
  {
    $this->app = $app;
  }

  public function before(array $params): void
  {
    $requestIdHeader = $this->app->request()->getHeader('X-Cff-Request-Id');
    $requestId = $requestIdHeader !== null && $requestIdHeader !== '' ? $requestIdHeader : 'no-request-id';

    if ($requestIdHeader !== null && $requestIdHeader !== '') {
      $this->app->response()->header('x-cff-request-id', $requestIdHeader);
    }

    $authTokenHeader = $this->app->request()->getHeader('X-CFF-Auth-Token');
    $authToken = $authTokenHeader !== null && $authTokenHeader !== '' ? $authTokenHeader : 'no agency/include keys';

    if ($authTokenHeader !== null && $authTokenHeader !== '') {
      $this->app->response()->header('x-cff-auth-token', $authTokenHeader);
    }


    $accessKeyHeader = $this->app->request()->getHeader('X-CFF-Security-Access-Key');
    $accessKey = $accessKeyHeader !== null && $accessKeyHeader !== '' ? $accessKeyHeader : 'no agency/include keys';
    if ($accessKeyHeader !== null && $accessKeyHeader !== '') {
      $this->app->response()->header('x-cff-security-access-key', $accessKeyHeader);
    }

    $secretKeyHeader = $this->app->request()->getHeader('X-CFF-Security-Secret-Key');
    $secretKey = $secretKeyHeader !== null && $secretKeyHeader !== '' ? $secretKeyHeader : 'no agency/include keys';
    if ($secretKeyHeader !== null && $secretKeyHeader !== '') {
      $this->app->response()->header('x-cff-security-secret-key', $secretKeyHeader);
    }


    $securityTokenHeader = $this->app->request()->getHeader('X-CFF-Security-Token');
    $securityToken = $securityTokenHeader !== null && $securityTokenHeader !== '' ? $securityTokenHeader : 'no agency/include keys';
    if ($securityTokenHeader !== null && $securityTokenHeader !== '') {
      $this->app->response()->header('x-cff-security-token', $securityTokenHeader);
    }


    $logLevel = strtoupper((string) getenv('RUNTIME_LOG_LEVEL'));
    if ($logLevel === '') {
      $logLevel = 'DEBUG';
    }

    $logger = new RequestLogger($requestId, $logLevel);
    $this->app->set('logger', $logger);

    $this->app->set('cffRequestId', $requestId);
    $this->app->set('cffAuthToken', $authToken);
    $this->app->set('cffAccessKey', $accessKey);
    $this->app->set('cffSecretKey', $secretKey);
    $this->app->set('cffSecurityToken', $securityToken);


    $logger->debug('Request started', $this->app->request()->method, $this->app->request()->url);
  }
}
