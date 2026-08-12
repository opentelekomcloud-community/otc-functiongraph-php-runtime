<?php

namespace app\middlewares;

use flight\Engine;
use Monolog\Logger;

// FunctionGraph related request headers
enum CFFHeader: string
{

  case AUTH_TOKEN = 'x-cff-auth-token';
  case FUNC_NAME = 'x-cff-func-name';
  case FUNC_VERSION = 'x-cff-func-version';
  case MEMORY = 'x-cff-memory';
  case ORIGIN_VERSION_TAG = 'x-cff-origin-version-tag';
  case PACKAGE = 'x-cff-package';
  case PROJECT_ID = 'x-cff-project-id';
  case REGION = 'x-cff-region';
  case REQUEST_ID = 'x-cff-request-id';
  case SECURITY_ACCESS_KEY = 'x-cff-security-access-key';
  case SECURITY_SECRET_KEY = 'x-cff-security-secret-key';
  case SECURITY_TOKEN = 'x-cff-security-token';
  case TIMEOUT = 'x-cff-timeout';
  case WORKFLOW_ID = 'x-cff-workflow-id';
  case WORKFLOW_RUN_ID = 'x-cff-workflow-run-id';
  case WORKFLOW_STATE_ID = 'x-cff-workflow-state-id';

}

class FGContainerMiddleware
{
  protected Engine $app;
  protected Logger $logger;
  public function __construct(Engine $app)
  {
    $this->app = $app;
    $this->logger = $app->log();
  }

  public function before(array $params): void
  {

    foreach (CFFHeader::cases() as $cffHeader) {
      $headerName = $cffHeader->value;
      $headerValue = $this->app->request()->getHeader($headerName);
      if ($headerValue !== null && $headerValue !== '') {
        $this->app->response()->header(strtolower($headerName), $headerValue);
      }
      $this->app->set(strtolower($headerName), $headerValue !== null && $headerValue !== '' ? $headerValue : 'no value');
    }

    $this->logger->debug('Request started', [
      'method' => $this->app->request()->method,
      'url' => $this->app->request()->url
    ]);

  }

  public function after(array $params): void
  {
    $this->logger->debug('Request ended', [
      'method' => $this->app->request()->method,
      'url' => $this->app->request()->url
    ]);
  }
}
