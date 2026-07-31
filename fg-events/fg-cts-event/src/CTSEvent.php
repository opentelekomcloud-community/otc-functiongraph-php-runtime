<?php

namespace OTC\fg_cts_event;

/**
 * Represents an CTS event for FunctionGraph.
 */
class CTSEvent
{
  private array $event;

  public function __construct($event)
  {
    $array = json_decode(json_encode($event), true);
    $this->event = ($array['cts'] ?? []) ?: [];
  }

  public function getTime(): int
  {
    return $this->event['time'] ?? 0;
  }
  public function getUser(): CTSUserInfo
  {
    return new CTSUserInfo($this->event['user'] ?? []);
  }
  public function getRequest(): array
  {
    return $this->event['request'] ?? [];
  }
  public function getResponse(): array
  {
    return $this->event['response'] ?? [];
  }
  public function getServiceType(): string
  {
    return $this->event['service_type'] ?? '';
  }
  public function getEventType(): string
  {
    return $this->event['event_type'] ?? '';
  }
  public function getProjectId(): string
  {
    return $this->event['project_id'] ?? '';
  }
  public function getResourceType(): string
  {
    return $this->event['resource_type'] ?? '';
  }
  public function getResourceAccountId(): string
  {
    return $this->event['resource_account_id'] ?? '';
  }
  public function getReadOnly(): bool
  {
    return $this->event['read_only'] ?? false;
  }
  public function getTrackerName(): string
  {
    return $this->event['tracker_name'] ?? '';
  }
  public function getOperationId(): string
  {
    return $this->event['operation_id'] ?? '';
  }
  public function getResourceName(): string
  {
    return $this->event['resource_name'] ?? '';
  }
  public function getResourceId(): string
  {
    return $this->event['resource_id'] ?? '';
  }
  public function getSourceIp(): string
  {
    return $this->event['source_ip'] ?? '';
  }
  public function getDomainId(): string
  {
    return $this->event['domain_id'] ?? '';
  }
  public function getTraceName(): string
  {
    return $this->event['trace_name'] ?? '';
  }
  public function getTraceStatus(): string
  {
    return $this->event['trace_status'] ?? '';
  }
  public function getTraceRating(): string
  {
    return $this->event['trace_rating'] ?? '';
  }
  public function getTraceType(): string
  {
    return $this->event['trace_type'] ?? '';
  }
  public function getApiVersion(): string
  {
    return $this->event['api_version'] ?? '';
  }
  public function getMessage(): string
  {
    return $this->event['message'] ?? '';
  }
  public function getRecordTime(): string
  {
    return $this->event['record_time'] ?? '';
  }
  public function getTraceId(): string
  {
    return $this->event['trace_id'] ?? '';
  }
  public function getCode(): string
  {
    return $this->event['code'] ?? '';
  }
  public function getRequestId(): string
  {
    return $this->event['request_id'] ?? '';
  }
  public function getLocationInfo(): array
  {
    return $this->event['location_info'] ?? [];
  }
  public function getEndpoint(): string
  {
    return $this->event['endpoint'] ?? '';
  }
  public function getResourceUrl(): string
  {
    return $this->event['resource_url'] ?? '';
  }
  public function getEnterpriseProjectId(): string
  {
    return $this->event['enterprise_project_id'] ?? '';
  }
  public function getUserAgent(): string
  {
    return $this->event['user_agent'] ?? '';
  }
  public function getContentLength(): int
  {
    return $this->event['content_length'] ?? 0;
  }
  public function getTotalTime(): int
  {
    return $this->event['total_time'] ?? 0;
  }
  public function toJson(): array
  {
    return $this->event;
  }
}
