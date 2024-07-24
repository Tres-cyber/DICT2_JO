<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
class Session
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\ManyToOne]
  #[ORM\JoinColumn(nullable: false)]
  private ?Account $account = null;

  #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
  private ?\DateTimeInterface $login = null;

  #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
  private ?\DateTimeInterface $logout = null;

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

  public function getLogin(): ?\DateTimeInterface
  {
    return $this->login;
  }

  public function setLogin(\DateTimeInterface $login): static
  {
    $this->login = $login;

    return $this;
  }

  public function getLogout(): ?\DateTimeInterface
  {
    return $this->logout;
  }

  public function setLogout(?\DateTimeInterface $logout): static
  {
    $this->logout = $logout;

    return $this;
  }
}
