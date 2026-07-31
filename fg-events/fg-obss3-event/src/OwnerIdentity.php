<?php

namespace OTC\fg_obss3_event;

/** Represents the bucket owner identity in an OBS S3 event. */
class OwnerIdentity
{
  private array $identity;

  public function __construct(array $identity)
  {
    $this->identity = $identity;
  }

  public function getPrincipalId(): string
  {
    return $this->identity['PrincipalId'] ?? '';
  }

  public function toJson(): array
  {
    return $this->identity;
  }
}
