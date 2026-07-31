<?php

namespace OTC\fg_smn_event;

/** Represents a Simple Message Notification event for FunctionGraph. */
class SMNEvent
{
  private array $event;
  /** @var SMNRecord[] */
  private array $records;

  public function __construct($event)
  {
    $array = json_decode(json_encode($event), true);
    $this->event = $array ?? [];
    $this->records = array_map(
      fn(array $r) => new SMNRecord($r),
      $this->event['record'] ?? []
    );
  }

  /** @return SMNRecord[] */
  public function getRecords(): array
  {
    return $this->records;
  }

  public function getFunctionName(): string
  {
    return $this->event['functionname'] ?? '';
  }

  public function getRequestId(): string
  {
    return $this->event['requestId'] ?? '';
  }

  public function getTimestamp(): string
  {
    return $this->event['timestamp'] ?? '';
  }

  public function toJson(): array
  {
    return $this->event;
  }
}
