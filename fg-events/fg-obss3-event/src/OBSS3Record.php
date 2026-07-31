<?php

namespace OTC\fg_obss3_event;

/** Represents a single OBS S3 event record. */
class OBSS3Record
{
  private array $record;

  public function __construct(array $record)
  {
    $this->record = $record;
  }

  public function getEventVersion(): string
  {
    return $this->record['eventVersion'] ?? '';
  }

  public function getEventTime(): string
  {
    return $this->record['eventTime'] ?? '';
  }

  public function getRequestParameters(): RequestParameters
  {
    return new RequestParameters($this->record['requestParameters'] ?? []);
  }

  public function getS3(): S3Details
  {
    return new S3Details($this->record['s3'] ?? []);
  }

  public function getAwsRegion(): string
  {
    return $this->record['awsRegion'] ?? '';
  }

  public function getEventName(): string
  {
    return $this->record['eventName'] ?? '';
  }

  public function getUserIdentity(): UserIdentity
  {
    return new UserIdentity($this->record['userIdentity'] ?? []);
  }

  public function toJson(): array
  {
    return $this->record;
  }
}
