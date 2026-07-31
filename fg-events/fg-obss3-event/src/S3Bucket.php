<?php

namespace OTC\fg_obss3_event;

/** Represents the bucket details in an OBS S3 event. */
class S3Bucket
{
  private array $bucket;

  public function __construct(array $bucket)
  {
    $this->bucket = $bucket;
  }

  public function getArn(): string
  {
    return $this->bucket['arn'] ?? '';
  }

  public function getName(): string
  {
    return $this->bucket['name'] ?? '';
  }

  public function getOwnerIdentity(): OwnerIdentity
  {
    return new OwnerIdentity($this->bucket['ownerIdentity'] ?? []);
  }

  public function toJson(): array
  {
    return $this->bucket;
  }
}
