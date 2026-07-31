<?php

namespace OTC\fg_dms4kafka_event;

/** Represents a single Kafka message in a DMS4Kafka record. */
class DMS4KafkaRecordMessage
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
