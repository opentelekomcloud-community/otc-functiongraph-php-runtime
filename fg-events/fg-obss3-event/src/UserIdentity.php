<?php

namespace OTC\fg_obss3_event;

/** Represents the user identity in an OBS S3 event. */
class UserIdentity
{
  private array $identity;

  public function __construct(array $identity)
  {
    $this->identity = $identity;
  }

  public function getPrincipalId(): string
  {
    return $this->identity['principalId'] ?? '';
  }

  public function toJson(): array
  {
    return $this->identity;
  }
}
