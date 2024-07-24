<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 127)]
  private ?string $name = null;

  #[ORM\Column(length: 63)]
  private ?string $code = null;

  #[ORM\Column(length: 127, nullable: true)]
  private ?string $logo = null;

  #[ORM\Column(options: ["default" => false])]
  private ?bool $is_deleted = false;

  #[ORM\ManyToOne]
  private ?Personnel $focal_person = null;

  public function __construct() {}

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(string $name): static
  {
    $this->name = $name;

    return $this;
  }

  public function getCode(): ?string
  {
    return $this->code;
  }

  public function setCode(string $code): static
  {
    $this->code = $code;

    return $this;
  }

  public function getLogo(): ?string
  {
    return $this->logo;
  }

  public function setLogo(string $logo): static
  {
    $this->logo = $logo;

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

  public function getFocalPerson(): ?Personnel
  {
    return $this->focal_person;
  }

  public function setFocalPerson(?Personnel $focal_person): static
  {
    $this->focal_person = $focal_person;

    return $this;
  }
}
