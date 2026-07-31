<?php

namespace OTC\fg_apig_event;

/**
 * Represents the request context of an API Gateway event.
 *
 * Attributes:
 *   apiId (string): The API ID
 *   requestId (string): The request ID
 *   stage (string): The stage
 */
class APIGRequestContext
{
    private array $requestContext;

    public function __construct(array $requestContext)
    {
        $this->requestContext = $requestContext;
    }

    /** Returns the API ID. */
    public function getApiId(): string
    {
        return $this->requestContext['apiId'] ?? '';
    }

    /** Returns the request ID. */
    public function getRequestId(): string
    {
        return $this->requestContext['requestId'] ?? '';
    }

    /** Returns the stage. */
    public function getStage(): string
    {
        return $this->requestContext['stage'] ?? '';
    }

    /**
     * Get a value from the request context by key.
     *
     * Returns null when the key is not present.
     */
    public function getValue(string $key): ?string
    {
        return isset($this->requestContext[$key]) ? (string) $this->requestContext[$key] : null;
    }

    /** Returns the raw request context data. */
    public function toJson(): array
    {
        return $this->requestContext;
    }
}
