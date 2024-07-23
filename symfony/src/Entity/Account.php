<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\OneToOne(cascade: ['persist', 'remove'])]
  private ?Personnel $personnel = null;

  #[ORM\Column(length: 127)]
  private ?string $password_hash = null;

  #[ORM\Column(length: 127, unique: true)]
  private ?string $email = null;

  #[ORM\Column(options: ['default' => false])]
  private ?bool $is_deleted = null;

  #[ORM\Column(options: ['default' => false])]
  private ?bool $is_admin = null;

  #[ORM\OneToOne(cascade: ['persist', 'remove'])]
  private ?Session $current_session = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getPersonnel(): ?Personnel
  {
    return $this->personnel;
  }

  public function setPersonnel(?Personnel $personnel): static
  {
    $this->personnel = $personnel;

    return $this;
  }

  public function getPasswordHash(): ?string
  {
    return $this->password_hash;
  }

  public function setPasswordHash(string $password_hash): static
  {
    $this->password_hash = $password_hash;

    return $this;
  }

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function setEmail(string $email): static
  {
    $this->email = $email;

    return $this;
  }

  public function isDeleted(): ?bool
  {
    return $this->is_deleted;
  }

  public function setDeleted(bool $is_deleted): static
  {
    $this->is_deleted = $is_deleted;

    return $this;
  }

  public function isAdmin(): ?bool
  {
    return $this->is_admin;
  }

  public function setAdmin(bool $is_admin): static
  {
    $this->is_admin = $is_admin;

    return $this;
  }

  public function getCurrentSession(): ?Session
  {
    return $this->current_session;
  }

  public function setCurrentSession(?Session $current_session): static
  {
    $this->current_session = $current_session;

    return $this;
  }
}
