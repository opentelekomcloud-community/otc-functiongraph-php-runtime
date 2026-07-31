<?php

namespace OTC\fg_dds_event;

/** Represents a single DDS event record. */
class DDSRecord
{
    private array $record;

    public function __construct(array $record)
    {
        $this->record = $record;
    }

    public function getEventSource(): string
    {
        return $this->record['event_source'] ?? '';
    }

    public function getEventVersion(): string
    {
        return $this->record['event_version'] ?? '';
    }

    public function getEventName(): string
    {
        return $this->record['event_name'] ?? '';
    }

    public function getEventSourceIp(): string
    {
        return $this->record['event_source_ip'] ?? '';
    }

    public function getRegion(): string
    {
        return $this->record['region'] ?? '';
    }

    public function getDds(): DDS
    {
        return new DDS($this->record['dds'] ?? []);
    }

    public function toJson(): array
    {
        return $this->record;
    }
}
