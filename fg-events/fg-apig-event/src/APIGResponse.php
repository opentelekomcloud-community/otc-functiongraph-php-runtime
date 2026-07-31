<?php

namespace OTC\fg_apig_event;

/**
 * Represents an API Gateway response for FunctionGraph.
 *
 * Attributes:
 *   statusCode (int): HTTP status code
 *   body (string): Response body
 *   headers (array): Response headers
 *   isBase64Encoded (bool): Whether the body is base64 encoded
 */
class APIGResponse
{
    public int $statusCode;
    public string $body;
    public array $headers;
    public bool $isBase64Encoded;

    public function __construct(
        int $statusCode = 200,
        string $body = '',
        array $headers = [],
        bool $isBase64Encoded = false
    ) {
        $this->statusCode = $statusCode;
        $this->body = $body;
        $this->headers = $headers;
        $this->isBase64Encoded = $isBase64Encoded;
    }

    /** Create an APIGResponse from a dictionary. */
    public static function fromJson(array $data): self
    {
        return new self(
            statusCode: $data['statusCode'] ?? 200,
            body: $data['body'] ?? '',
            headers: $data['headers'] ?? [],
            isBase64Encoded: $data['isBase64Encoded'] ?? false,
        );
    }

    /** Set the HTTP status code. */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * Set the response body.
     *
     * Non-string values are JSON-encoded; base64 encoding is applied when $isBase64Encoded is true.
     */
    public function setBody(string|array $body, bool $isBase64Encoded = false): void
    {
        $this->isBase64Encoded = $isBase64Encoded;

        $str = is_string($body) ? $body : json_encode($body);

        if ($isBase64Encoded) {
            $this->body = base64_encode($str);
        } else {
            $this->body = $str;
        }
    }

    /** Returns the raw response body as stored. */
    public function getRawBody(): string
    {
        return $this->body;
    }

    /** Returns the decoded response body (base64-decodes when isBase64Encoded is true). */
    public function getBody(): string
    {
        if ($this->isBase64Encoded) {
            return base64_decode($this->body);
        }
        return $this->body;
    }

    /**
     * Returns the parsed response body.
     *
     * Returns null when body is empty; otherwise JSON-decodes the plain-text body.
     */
    public function getBodyParsed(): mixed
    {
        if ($this->body === '') {
            return null;
        }
        return json_decode($this->getBody(), true);
    }

    /** Returns the response data as an array. */
    public function toJson(): array
    {
        return [
            'statusCode'     => $this->statusCode,
            'body'           => $this->body,
            'headers'        => $this->headers,
            'isBase64Encoded' => $this->isBase64Encoded,
        ];
    }
}
