<?php

namespace OTC\fg_smn_event;

/** Represents a single SMN event record. */
class SMNRecord
{
  private array $record;

  public function __construct(array $record)
  {
    $this->record = $record;
  }

  public function getEventSubscriptionUrn(): string
  {
    return $this->record['event_subscription_urn'] ?? '';
  }

  public function getEventSource(): string
  {
    return $this->record['event_source'] ?? '';
  }

  public function getSmnBody(): SMNBody
  {
    return new SMNBody($this->record['smn'] ?? []);
  }

  public function toJson(): array
  {
    return $this->record;
  }
}
