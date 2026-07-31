<?php

namespace OTC\fg_dms4kafka_event;

/** Represents a topic record in a DMS4Kafka event. */
class DMS4KafkaRecord
{
  private array $record;
  /** @var DMS4KafkaRecordMessage[] */
  private array $messages;

  public function __construct(array $record)
  {
    $this->record = $record;
    $this->messages = array_map(
      fn($m) => new DMS4KafkaRecordMessage($m),
      $record['messages'] ?? []
    );
  }

  public function getTopicId(): string
  {
    return $this->record['topic_id'] ?? '';
  }

  /** @return DMS4KafkaRecordMessage[] */
  public function getMessages(): array
  {
    return $this->messages;
  }

  public function toJson(): array
  {
    return $this->record;
  }
}
