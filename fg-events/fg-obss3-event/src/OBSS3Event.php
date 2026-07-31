<?php

namespace OTC\fg_obss3_event;

/** Represents an Object Storage Service S3 event for FunctionGraph. */
class OBSS3Event
{
  private array $event;
  /** @var OBSS3Record[] */
  private array $records;

  public function __construct($event)
  {
    $array = json_decode(json_encode($event), true);
    $this->event = $array ?? [];
    $this->records = array_map(
      fn(array $r) => new OBSS3Record($r),
      $this->event['Records'] ?? []
    );
  }

  /** @return OBSS3Record[] */
  public function getRecords(): array
  {
    return $this->records;
  }

  public function toJson(): array
  {
    return $this->event;
  }
}
