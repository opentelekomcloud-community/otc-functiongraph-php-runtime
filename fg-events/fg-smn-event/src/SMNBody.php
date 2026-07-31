<?php

namespace OTC\fg_smn_event;

/** Represents the SMN message body in a record. */
class SMNBody
{
  private array $smn;

  public function __construct(array $smn)
  {
    $this->smn = $smn;
  }

  public function getTopicUrn(): string
  {
    return $this->smn['topic_urn'] ?? '';
  }

  public function getTimestamp(): string
  {
    return $this->smn['timestamp'] ?? '';
  }

  public function getMessageAttributes(): mixed
  {
    return $this->smn['message_attributes'] ?? null;
  }

  public function getMessage(): string
  {
    return $this->smn['message'] ?? '';
  }

  public function getType(): string
  {
    return $this->smn['type'] ?? '';
  }

  public function getMessageId(): string
  {
    return $this->smn['message_id'] ?? '';
  }

  public function getSubject(): string
  {
    return $this->smn['subject'] ?? '';
  }

  public function toJson(): array
  {
    return $this->smn;
  }
}
