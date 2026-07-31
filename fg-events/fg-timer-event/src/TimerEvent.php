<?php

namespace OTC\fg_timer_event;

/**
 * TimerEvent class for FunctionGraph timer events.
 */
class TimerEvent
{
    private array $event;

    public function __construct($event)
    {
        $array = json_decode(json_encode($event), true);
        $this->event = $array;
    }

    public function getVersion(): string
    {
        return $this->event['version'] ?? '';
    }

    public function getTime(): string
    {
        return $this->event['time'] ?? '';
    }

    public function getTriggerType(): string
    {
        return $this->event['trigger_type'] ?? '';
    }

    public function getTriggerName(): string
    {
        return $this->event['trigger_name'] ?? '';
    }

    public function getUserEvent(): string
    {
        return $this->event['user_event'] ?? '';
    }

    /** Returns the parsed user_event JSON, or null if invalid. */
    public function getUserEventParsed(): ?array
    {
        $raw = $this->event['user_event'] ?? null;
        if ($raw === null) {
            return null;
        }
        $decoded = json_decode($raw, true);
        return (json_last_error() === JSON_ERROR_NONE) ? $decoded : null;
    }

    public function toJson(): array
    {
        return $this->event;
    }
}
