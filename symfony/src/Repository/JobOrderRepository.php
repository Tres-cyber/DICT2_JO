<?php

namespace App\Repository;

use App\Entity\JobOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    $dql = "SELECT COUNT(jo) FROM App\Entity\JobOrder jo 
        WHERE jo.project = :projectId 
        AND SUBSTRING(jo.request_date, 1, 7) = :yearMonth 
        AND jo.status != 'DRAFT'";

    $query = $em->createQuery($dql);
    $query->setParameter('projectId', $project->getId());
    $query->setParameter('yearMonth', $yearMonth);

    $count = intval($query->getSingleScalarResult());

    $formattedDate = $date->format('Y-m-d');
    return sprintf("%s-%s-%s-S%02d", $projectCode, 'R2', $formattedDate, $count + 1);
  }
}
