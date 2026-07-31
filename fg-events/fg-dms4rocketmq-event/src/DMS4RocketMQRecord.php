<?php

namespace OTC\fg_dms4rocketmq_event;

/** Represents a topic record in a DMS4RocketMQ event. */
class DMS4RocketMQRecord
{
  private array $record;
  /** @var DMS4RocketMQRecordMessage[] */
  private array $messages;

  public function __construct(array $record)
  {
    $this->record = $record;
    $this->messages = array_map(
      fn($m) => new DMS4RocketMQRecordMessage($m),
      $record['messages'] ?? []
    );
  }

  public function getTopicId(): string
  {
    return $this->record['topic_id'] ?? '';
  }

  /** @return DMS4RocketMQRecordMessage[] */
  public function getMessages(): array
  {
    return $this->messages;
  }

  public function toJson(): array
  {
    return $this->record;
  }
}
