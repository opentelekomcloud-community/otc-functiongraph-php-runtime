<?php

namespace OTC\fg_dms4kafka_event;

/** Represents a DMS4Kafka event for FunctionGraph. */
class DMS4KafkaEvent
{
  private array $event;
  /** @var DMS4KafkaRecord[] */
  private array $records;

  public function __construct($event)
  {
    $array = json_decode(json_encode($event), true);
    $this->event = $array ?? [];
    $this->records = array_map(
      fn(array $r) => new DMS4KafkaRecord($r),
      $this->event['records'] ?? []
    );
  }

  public function getEventVersion(): string
  {
    return $this->event['event_version'] ?? '';
  }

  public function getEventTime(): string
  {
    return $this->event['event_time'] ?? '';
  }

  public function getRegion(): string
  {
    return $this->event['region'] ?? '';
  }

  public function getTriggerType(): string
  {
    return $this->event['trigger_type'] ?? '';
  }

  public function getInstanceId(): string
  {
    return $this->event['instance_id'] ?? '';
  }

  /** @return DMS4KafkaRecord[] */
  public function getRecords(): array
  {
    return $this->records;
  }

  public function toJson(): array
  {
    return $this->event;
  }
}
