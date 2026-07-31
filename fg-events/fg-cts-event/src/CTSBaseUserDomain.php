<?php

namespace OTC\fg_cts_event;

class CTSBaseUserDomain
{
  private array $userDomain;

  public function __construct(array $userDomain)
  {
    $this->userDomain = $userDomain;
  }

  public function getId(): string
  {
    return $this->userDomain['id'] ?? '';
  }
  public function getName(): string
  {
    return $this->userDomain['name'] ?? '';
  }
  public function toJson(): array
  {
    return $this->userDomain;
  }
}
