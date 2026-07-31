<?php

namespace OTC\fg_dms4rocketmq_event;

/** Represents a DMS4RocketMQ event for FunctionGraph. */
class DMS4RocketMQEvent
{
  private array $event;

  public function __construct($event)
  {
    $array = json_decode(json_encode($event), true);
    $this->event = $array ?? [];
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

  /** @return DMS4RocketMQRecord[] */
  public function getRecords(): array
  {
    return array_map(
      fn(array $r) => new DMS4RocketMQRecord($r),
      $this->event['records'] ?? []
    );
  }

  public function toJson(): array
  {
    return $this->event;
  }
}
