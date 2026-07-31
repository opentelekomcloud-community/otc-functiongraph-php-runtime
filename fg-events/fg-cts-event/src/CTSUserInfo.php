<?php

namespace OTC\fg_cts_event;

class CTSUserInfo
{
  private array $user;

  public function __construct(array $user)
  {
    $this->user = $user;
  }

  public function getType(): string
  {
    return $this->user['type'] ?? '';
  }
  public function getPrincipalId(): string
  {
    return $this->user['principal_id'] ?? '';
  }
  public function getPrincipalUrn(): string
  {
    return $this->user['principal_urn'] ?? '';
  }
  public function getAccountId(): string
  {
    return $this->user['account_id'] ?? '';
  }
  public function getAccessKeyId(): string
  {
    return $this->user['access_key_id'] ?? '';
  }
  public function getId(): string
  {
    return $this->user['id'] ?? '';
  }
  public function getName(): string
  {
    return $this->user['name'] ?? '';
  }
  public function getDomain(): CTSBaseUserDomain
  {
    return new CTSBaseUserDomain($this->user['domain'] ?? []);
  }
  public function getUserName(): string
  {
    return $this->user['user_name'] ?? '';
  }
  public function getPrincipalIsRootUser(): string
  {
    return $this->user['principal_is_root_user'] ?? '';
  }
  public function getInvokedBy(): array
  {
    return $this->user['invoked_by'] ?? [];
  }
  public function getSessionContext(): CTSSessionContext
  {
    return new CTSSessionContext($this->user['session_context'] ?? []);
  }
  public function getOriginUser(): string
  {
    return $this->user['OriginUser'] ?? '';
  }
  public function toJson(): array
  {
    return $this->user;
  }
}
