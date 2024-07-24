<?php

namespace App\Entity;

use App\Repository\AccountSessionRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccountSessionRepository::class)]
class AccountSession
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\ManyToOne]
  #[ORM\JoinColumn(nullable: false)]
  private ?Account $account = null;

  #[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP'])]
  private ?\DateTimeImmutable $login_at = new DateTimeImmutable();

  #[ORM\Column(nullable: true)]
  private ?\DateTimeImmutable $logout_at = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getAccount(): ?Account
  {
    return $this->account;
  }

  public function setAccount(?Account $account): static
  {
    $this->account = $account;

    return $this;
  }

  public function getLoginAt(): ?\DateTimeImmutable
  {
    return $this->login_at;
  }

  public function setLoginAt(\DateTimeImmutable $login_at): static
  {
    $this->login_at = $login_at;

    return $this;
  }

  public function getLogoutAt(): ?\DateTimeImmutable
  {
    return $this->logout_at;
  }

  public function setLogoutAt(?\DateTimeImmutable $logout_at): static
  {
    $this->logout_at = $logout_at;

    return $this;
  }
}
