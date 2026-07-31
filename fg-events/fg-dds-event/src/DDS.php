<?php

namespace OTC\fg_dds_event;

/** Represents the DDS payload of an event record. */
class DDS
{
    private array $dds;

    public function __construct(array $dds)
    {
        $this->dds = $dds;
    }

    public function getSizeBytes(): int
    {
        return $this->dds['size_bytes'] ?? 0;
    }

    /** Returns the raw token string as stored in the event. */
    public function getTokenRaw(): string
    {
        return $this->dds['token'] ?? '';
    }

    /** Returns the token decoded from JSON; falls back to an empty array. */
    public function getToken(): array
    {
        return self::parseJsonValue($this->dds['token'] ?? null, []);
    }

    /** Returns the raw full_document value as stored in the event. */
    public function getFullDocumentRaw(): array
    {
        return $this->dds['full_document'] ?? [];
    }

    /** Returns full_document decoded from JSON; falls back to an empty array. */
    public function getFullDocument(): array
    {
        return self::parseJsonValue($this->dds['full_document'] ?? null, []);
    }

    /** Returns the raw ns string as stored in the event. */
    public function getNsRaw(): string
    {
        return $this->dds['ns'] ?? '';
    }

    /** Returns ns decoded from JSON; falls back to an empty array. */
    public function getNs(): array
    {
        return self::parseJsonValue($this->dds['ns'] ?? null, []);
    }

    public function toJson(): array
    {
        return $this->dds;
    }

    /** Decodes a value that may already be an array or a JSON string. */
    private static function parseJsonValue(mixed $value, array $default): array
    {
        if (is_array($value)) {
            return $value;
        }
        if (!is_string($value) || $value === '') {
            return $default;
        }
        $decoded = json_decode($value, true);
        return (json_last_error() === JSON_ERROR_NONE) ? $decoded : $default;
    }
}
