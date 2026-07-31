<?php

namespace OTC\fg_obss3_event;

/** Represents the object details in an OBS S3 event. */
class S3Object
{
  private array $obj;

  public function __construct(array $obj)
  {
    $this->obj = $obj;
  }

  public function getETag(): string
  {
    return $this->obj['eTag'] ?? '';
  }

  public function getSequencer(): string
  {
    return $this->obj['sequencer'] ?? '';
  }

  public function getKey(): string
  {
    return $this->obj['key'] ?? '';
  }

  public function getSize(): int
  {
    return $this->obj['size'] ?? 0;
  }

  public function toJson(): array
  {
    return $this->obj;
  }
}
