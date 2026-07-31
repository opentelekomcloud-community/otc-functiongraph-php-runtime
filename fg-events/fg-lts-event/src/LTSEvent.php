<?php

namespace OTC\fg_lts_event;

/** Represents an LTS event for FunctionGraph. */
class LTSEvent
{
  private array $event;

  public function __construct($event)
  {
    $array = json_decode(json_encode($event), true);
    $this->event = $array;
  }

  public function getRawData(): string
  {
    return $this->event['lts']['data'] ?? '';
  }

  public function getData(): string
  {
    $raw = $this->getRawData();
    if ($raw === '') {
      return '';
    }
    // LTS delivers the payload as base64-encoded UTF-8 text.
    $decoded = base64_decode($raw, true);
    return ($decoded === false) ? '' : $decoded;
  }

  public function getLogs()
  {
    $data = $this->getData();
    if ($data === '') {
      return [];
    }
    $json = json_decode($data, true);
    // Invalid or partial payloads are treated as having no log entries.
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($json)) {
      return [];
    }
    return $json['logs'] ?? [];
  }

  public function toJson(): array
  {
    return $this->event;
  }
}
