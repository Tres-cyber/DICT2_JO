<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account implements UserInterface, PasswordAuthenticatedUserInterface
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\OneToOne(cascade: ['persist', 'remove'])]
  private ?Personnel $personnel = null;

  #[ORM\Column(length: 127)]
  private ?string $password = null;

  #[ORM\Column(length: 127, unique: true)]
  private ?string $email = null;

  #[ORM\Column(options: ['default' => false])]
  private ?bool $is_deleted = false;

  #[ORM\Column(options: ['default' => false])]
  private ?bool $is_admin = false;

  #[ORM\OneToOne(cascade: ['persist', 'remove'])]
  private ?AccountSession $current_session = null;

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

  public function getRoles(): array
  {
    $roles = ['ROLE_USER'];
    if ($this->isAdmin()) {
      $roles[] = 'ROLE_ADMIN';
    }
    return $roles;
  }

  public function getUserIdentifier(): string
  {
    return (string) $this->email;
  }

  public function eraseCredentials(): void {}

  public function setPassword(string $password): static
  {
    $this->password = $password;
    return $this;
  }

  public function getPassword(): ?string
  {
    return $this->password;
  }

  public function getCurrentSession(): ?AccountSession
  {
    return $this->current_session;
  }

  public function hasSession(): bool
  {
    return !is_null($this->current_session);
  }

  public function setCurrentSession(?AccountSession $current_session): static
  {
    $this->current_session = $current_session;

    return $this;
  }
}
