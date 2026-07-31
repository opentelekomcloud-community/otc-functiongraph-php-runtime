<?php

namespace OTC\fg_kafkaopensource_event;

/** Represents a topic record in a Kafka Open Source event. */
class KafkaOpenSourceRecord
{
  private array $record;
  /** @var KafkaOpenSourceRecordMessage[] */
  private array $messages;

  public function __construct(array $record)
  {
    $this->record = $record;
    $this->messages = array_map(
      fn($m) => new KafkaOpenSourceRecordMessage($m),
      $record['messages'] ?? []
    );
  }

  public function getTopicId(): string
  {
    return $this->record['topic_id'] ?? '';
  }

  /** @return KafkaOpenSourceRecordMessage[] */
  public function getMessages(): array
  {
    return $this->messages;
  }

  public function toJson(): array
  {
    return $this->record;
  }
}
