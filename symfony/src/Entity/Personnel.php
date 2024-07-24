<?php

namespace App\Entity;

use App\Repository\PersonnelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonnelRepository::class)]
class Personnel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 127)]
    private ?string $name = null;

    #[ORM\Column(length: 63)]
    private ?string $position = null;

    #[ORM\ManyToOne(inversedBy: 'focal_person')]
    private ?Project $project = null;

    /**
     * @var Collection<int, JobOrder>
     */
    #[ORM\OneToMany(targetEntity: JobOrder::class, mappedBy: 'performer')]
    private Collection $jobOrders;

    public function __construct()
    {
        $this->jobOrders = new ArrayCollection();
    }

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

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return Collection<int, JobOrder>
     */
    public function getJobOrders(): Collection
    {
        return $this->jobOrders;
    }

    public function addJobOrder(JobOrder $jobOrder): static
    {
        if (!$this->jobOrders->contains($jobOrder)) {
            $this->jobOrders->add($jobOrder);
            $jobOrder->setPerformer($this);
        }

        return $this;
    }

    public function removeJobOrder(JobOrder $jobOrder): static
    {
        if ($this->jobOrders->removeElement($jobOrder)) {
            // set the owning side to null (unless already changed)
            if ($jobOrder->getPerformer() === $this) {
                $jobOrder->setPerformer(null);
            }
        }

        return $this;
    }
}
