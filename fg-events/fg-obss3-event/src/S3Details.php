<?php

namespace OTC\fg_obss3_event;

/** Represents the S3 payload in an OBS S3 event record. */
class S3Details
{
  private array $s3;

  public function __construct(array $s3)
  {
    $this->s3 = $s3;
  }

  public function getConfigurationId(): string
  {
    return $this->s3['configurationId'] ?? '';
  }

  public function getObject(): S3Object
  {
    return new S3Object($this->s3['object'] ?? []);
  }

  public function getBucket(): S3Bucket
  {
    return new S3Bucket($this->s3['bucket'] ?? []);
  }

  public function toJson(): array
  {
    return $this->s3;
  }
}
