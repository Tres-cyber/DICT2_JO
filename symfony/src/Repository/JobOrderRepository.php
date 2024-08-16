<?php

namespace App\Repository;

use App\Entity\JobOrder;
use App\Entity\Project;
use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JobOrder>
 */
class JobOrderRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, JobOrder::class);
  }

  public function createJoinedQueryBuilder(
    string $joborder = 'joborder',
    string $project = 'project',
    string $performer = 'performer',
    string $issuer = 'issuer',
    string $approver = 'approver'
  ): QueryBuilder {
    $qb = $this->createQueryBuilder($joborder)
      ->leftJoin($joborder . '.project', $project)
      ->leftJoin($joborder . '.performer', $performer)
      ->leftJoin($joborder . '.issuer', $issuer)
      ->leftJoin($joborder . '.approver', $approver)
      ->select($joborder, $project, $performer, $issuer, $approver);

    return $qb;
  }

  public function generateControlNumber(JobOrder $jobOrder): string
  {
    $em = $this->getEntityManager();

    $project = $jobOrder->getProject();
    $projectCode = $jobOrder->getProject()->getCode();


    $date = $jobOrder->getRequestDate();
    $yearMonth = $date->format('Y-m');

    $rsm = new ResultSetMapping();
    $count = $em->createNativeQuery("
        SELECT COUNT(*) 
        FROM job_order
        WHERE 
            project_id = :projectId
            AND DATE_FORMAT(request_date, '%Y-%m') = :yearMonth
            AND joborder_status != 'DRAFT'
    ", $rsm)->setParameters([
      ':projectId' => $project->getId(),
      ':yearMonth' => $yearMonth,
    ])->getSingleScalarResult();

    $formattedDate = $date->format('Y-m-d');
    return sprintf("%s-%s-%s-S%02d", $projectCode, 'R2', $formattedDate, $count + 1);
  }
}
