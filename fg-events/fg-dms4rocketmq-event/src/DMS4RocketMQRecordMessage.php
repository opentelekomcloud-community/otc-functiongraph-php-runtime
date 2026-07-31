<?php

namespace OTC\fg_dms4rocketmq_event;

/** Represents a single message in a DMS4RocketMQ record. */
class DMS4RocketMQRecordMessage
{
  private mixed $record;

  public function __construct(mixed $record)
  {
    $this->record = $record ?? '';
  }

  public function getMessage(): string
  {
    // The payload may be delivered as a bare string or as an object with a "message" key.
    if (is_string($this->record)) {
      return $this->record;
    }
    return $this->record['message'] ?? '';
  }

  public function toJson(): mixed
  {
    return $this->record;
  }
}
