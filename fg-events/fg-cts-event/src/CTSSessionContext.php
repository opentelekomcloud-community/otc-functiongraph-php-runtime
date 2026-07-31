<?php

namespace OTC\fg_cts_event;

class CTSSessionContext
{
  private array $sessionContext;

  public function __construct(array $sessionContext)
  {
    $this->sessionContext = $sessionContext;
  }

  public function getAttributes(): CTSSessionContextAttributes
  {
    return new CTSSessionContextAttributes($this->sessionContext['attributes'] ?? []);
  }

  public function toJson(): array
  {
    return $this->sessionContext;
  }
}
