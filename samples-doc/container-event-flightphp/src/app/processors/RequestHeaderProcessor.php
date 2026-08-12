<?php

namespace app\processors;

use Monolog\Level;
use Monolog\LogRecord;

// This class is a Monolog processor that adds selected request headers to the log record's extra data.
class RequestHeaderProcessor
{
  private Level $level;

  public function __construct(Level $level = Level::Debug)
  {
    $this->level = $level;
  }

  public function __invoke(LogRecord $record): LogRecord
  {
    if ($record->level->isLowerThan($this->level)) {
      return $record;
    }

    $additionalContext = [
      'DOCKER_BUILD_TIMESTAMP' => getenv('DOCKER_BUILD_TIMESTAMP') ?: 'unknown'
    ];

    return $record->with(
      extra: array_merge($record->extra, $this->getSelectedHeaders()),
      context: array_merge($record->context, $additionalContext)
    );
  }

  /**
   * @return array
   */
  protected function getSelectedHeaders()
  {

    $headers = [];

    // add following headers to the log record extra data
    $copy_server = [
      'X_CFF_REQUEST_ID' => 'x-cff-request-id',
    ];

    foreach ($_SERVER as $key => $value) {
      if (substr($key, 0, 5) === 'HTTP_') {
        $ckey = substr($key, 5);

        if (isset($copy_server[$ckey]) && isset($_SERVER[$key])) {
          $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $key))));
          $headers[$ckey] = $value;
        }
      }

    }


    return ($headers === false) ? [] : $headers;
    // }
  }
}