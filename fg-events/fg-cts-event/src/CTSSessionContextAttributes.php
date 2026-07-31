<?php

namespace OTC\fg_cts_event;

class CTSSessionContextAttributes
{
  private array $sessionAttributes;

  public function __construct(array $sessionAttributes)
  {
    $this->sessionAttributes = $sessionAttributes;
  }

  public function getCreatedAt(): string
  {
    return $this->sessionAttributes['created_at'] ?? '';
  }
  public function getMfaAuthenticated(): string
  {
    return $this->sessionAttributes['mfa_authenticated'] ?? '';
  }
  public function toJson(): array
  {
    return $this->sessionAttributes;
  }
}
