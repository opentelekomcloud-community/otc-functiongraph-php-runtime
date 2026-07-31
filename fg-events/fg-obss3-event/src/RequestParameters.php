<?php

namespace OTC\fg_obss3_event;

/** Represents the request parameters in an OBS S3 event record. */
class RequestParameters
{
  private array $params;

  public function __construct(array $params)
  {
    $this->params = $params;
  }

  public function getSourceIpAddress(): string
  {
    return $this->params['sourceIPAddress'] ?? '';
  }

  public function toJson(): array
  {
    return $this->params;
  }
}
