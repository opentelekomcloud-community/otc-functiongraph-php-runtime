<?php

namespace OTC\fg_apig_event;

/**
 * Represents an API Gateway event for FunctionGraph.
 *
 * Attributes:
 *   - body (string): The request body
 *   - isBase64Encoded (bool): Whether the body is base64 encoded
 *   - requestContext (array): Request context information
 *   - queryStringParameters (array): Query string parameters
 *   - httpMethod (string): HTTP method
 *   - pathParameters (array): Path parameters
 *   - headers (array): Request headers
 *   - path (string): Request path
 */
class APIGEvent
{
  private array $event;

  public function __construct($event)
  {
    $array = json_decode(json_encode($event), true);
    $this->event = $array;
  }

  /**
   * Returns the decoded request body.
   *
   * Returns plain text, base64-decoding first when isBase64Encoded is true.
   */
  public function getBody(): string
  {
    $body = $this->event['body'] ?? '';
    if ($this->event['isBase64Encoded'] ?? false) {
      if (!is_string($body) || $body === '') {
        return '';
      }
      $decoded = base64_decode($body, true);
      return ($decoded === false) ? '' : $decoded;
    }
    return $body;
  }

  /** Returns the raw request body as received. */
  public function getRawBody(): string
  {
    return $this->event['body'] ?? '';
  }

  /** Returns the request context. */
  public function getRequestContext(): APIGRequestContext
  {
    return new APIGRequestContext($this->event['requestContext'] ?? []);
  }

  /**
   * Get a value from the request context by key.
   *
   * Falls back to the lowercased key when the original is not found.
   */
  public function getRequestContextValue(string $key): string
  {
    $ctx = $this->getRequestContext();
    return $ctx->getValue($key) ?? $ctx->getValue(strtolower($key)) ?? '';
  }

  /** Returns all query string parameters. */
  public function getQueryStringParameters(): array
  {
    return $this->event['queryStringParameters'] ?? [];
  }

  /**
   * Get a query string parameter by name.
   *
   * Falls back to the lowercased name when the original is not found.
   */
  public function getQueryStringParameter(string $paramName): string
  {
    $params = $this->getQueryStringParameters();
    return $params[$paramName] ?? $params[strtolower($paramName)] ?? '';
  }

  /** Returns the HTTP method. */
  public function getHttpMethod(): string
  {
    return $this->event['httpMethod'] ?? '';
  }

  /** Returns all path parameters. */
  public function getPathParameters(): array
  {
    return $this->event['pathParameters'] ?? [];
  }

  /**
   * Get a path parameter by name.
   *
   * Falls back to the lowercased name when the original is not found.
   */
  public function getPathParameter(string $paramName): string
  {
    $params = $this->getPathParameters();
    return $params[$paramName] ?? $params[strtolower($paramName)] ?? '';
  }

  /** Returns all request headers. */
  public function getHeaders(): array
  {
    return $this->event['headers'] ?? [];
  }

  /**
   * Get a header value by name.
   *
   * Falls back to the lowercased name when the original is not found.
   */
  public function getHeader(string $headerName): string
  {
    $headers = $this->getHeaders();
    return $headers[$headerName] ?? $headers[strtolower($headerName)] ?? '';
  }

  /** Returns the request path. */
  public function getPath(): string
  {
    return $this->event['path'] ?? '';
  }

  /** Returns whether the body is base64 encoded. */
  public function isBase64Encoded(): bool
  {
    return $this->event['isBase64Encoded'] ?? false;
  }

  /** Returns the raw event data. */
  public function toJson(): array
  {
    return $this->event;
  }
}
