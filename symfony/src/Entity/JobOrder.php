<?php

namespace App\Entity;

use App\Repository\JobOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use \App\Entity\JobOrderStatus;
use \App\Entity\RequestMode;
use DateTimeImmutable;

#[ORM\Entity(repositoryClass: JobOrderRepository::class)]
class JobOrder
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\ManyToOne]
  #[ORM\JoinColumn(nullable: false)]
  private ?Project $project = null;

  #[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP'])]
  private ?\DateTimeImmutable $created_at = new DateTimeImmutable();

  #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
  private ?\DateTimeInterface $scheduled_start_date = null;

  #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
  private ?\DateTimeInterface $scheduled_end_date = null;

  #[ORM\ManyToOne(inversedBy: 'jobOrders')]
  private ?Personnel $performer = null;

  #[ORM\Column(type: Types::TEXT, nullable: true)]
  private ?string $job_description = null;

  #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
  private ?\DateTimeInterface $start_time = null;

  #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
  private ?\DateTimeInterface $end_time = null;

  #[ORM\Column(type: Types::TEXT, nullable: true)]
  private ?string $actual_job_done = null;

  #[ORM\Column(type: Types::TEXT, nullable: true)]
  private ?string $remarks = null;

  #[ORM\Column(length: 127, nullable: true)]
  private ?string $client_name = null;

  #[ORM\Column(length: 127, nullable: true)]
  private ?string $client_contact = null;

  #[ORM\Column(length: 127, nullable: true)]
  private ?string $client_lgu = null;

  #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
  private ?\DateTimeInterface $request_date = null;

  #[ORM\ManyToOne]
  private ?Personnel $issuer = null;

  #[ORM\ManyToOne]
  private ?Personnel $approver = null;

  #[ORM\Column(length: 63, nullable: true)]
  private ?string $control_number = null;

  #[ORM\Column(length: 127, nullable: true)]
  private ?string $verifier_name = null;

  #[ORM\Column(length: 63, nullable: true)]
  private ?string $verifier_position = null;

  #[ORM\Column(name: "joborder_status", enumType: JobOrderStatus::class, options: ['default' => 'DRAFT'])]
  private ?JobOrderStatus $status = JobOrderStatus::Draft;

  /**
   * @var Collection<int, Personnel>
   */
  #[ORM\ManyToMany(targetEntity: Personnel::class)]
  private Collection $endorsee;

  #[ORM\Column(enumType: RequestMode::class, options: ['default' => 'ON_SITE'])]
  private ?RequestMode $request_mode = RequestMode::OnSite;

  public function __construct()
  {
    $this->endorsee = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
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

  public function getCreatedAt(): ?\DateTimeImmutable
  {
    return $this->created_at;
  }

  public function setCreatedAt(\DateTimeImmutable $created_at): static
  {
    $this->created_at = $created_at;

    return $this;
  }

  public function getScheduledStartDate(): ?\DateTimeInterface
  {
    return $this->scheduled_start_date;
  }

  public function setScheduledStartDate(?\DateTimeInterface $scheduled_start_date): static
  {
    $this->scheduled_start_date = $scheduled_start_date;

    return $this;
  }

  public function getScheduledEndDate(): ?\DateTimeInterface
  {
    return $this->scheduled_end_date;
  }

  public function setScheduledEndDate(?\DateTimeInterface $scheduled_end_date): static
  {
    $this->scheduled_end_date = $scheduled_end_date;

    return $this;
  }

  public function getPerformer(): ?Personnel
  {
    return $this->performer;
  }

  public function setPerformer(?Personnel $performer): static
  {
    $this->performer = $performer;

    return $this;
  }

  public function getJobDescription(): ?string
  {
    return $this->job_description;
  }

  public function setJobDescription(?string $job_description): static
  {
    $this->job_description = $job_description;

    return $this;
  }

  public function getStartTime(): ?\DateTimeInterface
  {
    return $this->start_time;
  }

  public function setStartTime(?\DateTimeInterface $start_time): static
  {
    $this->start_time = $start_time;

    return $this;
  }

  public function getEndTime(): ?\DateTimeInterface
  {
    return $this->end_time;
  }

  public function setEndTime(?\DateTimeInterface $end_time): static
  {
    $this->end_time = $end_time;

    return $this;
  }

  public function getActualJobDone(): ?string
  {
    return $this->actual_job_done;
  }

  public function setActualJobDone(?string $actual_job_done): static
  {
    $this->actual_job_done = $actual_job_done;

    return $this;
  }

  public function getRemarks(): ?string
  {
    return $this->remarks;
  }

  public function setRemarks(?string $remarks): static
  {
    $this->remarks = $remarks;

    return $this;
  }

  public function getClientName(): ?string
  {
    return $this->client_name;
  }

  public function setClientName(?string $client_name): static
  {
    $this->client_name = $client_name;

    return $this;
  }

  public function getClientContact(): ?string
  {
    return $this->client_contact;
  }

  public function setClientContact(?string $client_contact): static
  {
    $this->client_contact = $client_contact;

    return $this;
  }

  public function getClientLgu(): ?string
  {
    return $this->client_lgu;
  }

  public function setClientLgu(?string $client_lgu): static
  {
    $this->client_lgu = $client_lgu;

    return $this;
  }

  public function getRequestMode(): ?string
  {
    return $this->request_mode;
  }

  public function setRequestMode(string $request_mode): static
  {
    $this->request_mode = $request_mode;

    return $this;
  }

  public function getRequestDate(): ?\DateTimeInterface
  {
    return $this->request_date;
  }

  public function setRequestDate(?\DateTimeInterface $request_date): static
  {
    $this->request_date = $request_date;

    return $this;
  }

  public function getIssuer(): ?Personnel
  {
    return $this->issuer;
  }

  public function setIssuer(?Personnel $issuer): static
  {
    $this->issuer = $issuer;

    return $this;
  }

  public function getApprover(): ?Personnel
  {
    return $this->approver;
  }

  public function setApprover(?Personnel $approver): static
  {
    $this->approver = $approver;

    return $this;
  }

  public function getControlNumber(): ?string
  {
    return $this->control_number;
  }

  public function setControlNumber(?string $control_number): static
  {
    $this->control_number = $control_number;

    return $this;
  }

  public function getVerifierName(): ?string
  {
    return $this->verifier_name;
  }

  public function setVerifierName(?string $verifier_name): static
  {
    $this->verifier_name = $verifier_name;

    return $this;
  }

  public function getVerifierPosition(): ?string
  {
    return $this->verifier_position;
  }

  public function setVerifierPosition(?string $verifier_position): static
  {
    $this->verifier_position = $verifier_position;

    return $this;
  }

  public function getStatus(): ?JobOrderStatus
  {
    return $this->status;
  }

  public function setStatus(JobOrderStatus $status): static
  {
    $this->status = $status;

    return $this;
  }

  /**
   * @return Collection<int, Personnel>
   */
  public function getEndorsee(): Collection
  {
    return $this->endorsee;
  }

  public function addEndorsee(Personnel $endorsee): static
  {
    if (!$this->endorsee->contains($endorsee)) {
      $this->endorsee->add($endorsee);
    }

    return $this;
  }

  public function removeEndorsee(Personnel $endorsee): static
  {
    $this->endorsee->removeElement($endorsee);

    return $this;
  }
}
