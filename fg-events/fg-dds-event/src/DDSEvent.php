<?php

namespace OTC\fg_dds_event;

/** Represents a DDS event for FunctionGraph. */
class DDSEvent
{
  private array $event;
  /** @var DDSRecord[] */
  private array $records;

  public function __construct($event)
  {
    $array = json_decode(json_encode($event), true);
    $this->event = $array;
    $this->records = array_map(
      fn(array $r) => new DDSRecord($r),
      $array['records'] ?? []
    );
  }

  /** @return DDSRecord[] */
  public function getRecords(): array
  {
    return $this->records;
  }

  public function getRecord(int $index): ?DDSRecord
  {
    return $this->records[$index] ?? null;
  }

  public function toJson(): array
  {
    return $this->event;
  }
}
