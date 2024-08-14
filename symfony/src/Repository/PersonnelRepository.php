<?php

namespace App\Repository;

use App\Entity\Personnel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Personnel>
 */
class PersonnelRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Personnel::class);
  }

  public function findAllJoined()
  {
    $entityManager = $this->getEntityManager();
    $query = $entityManager->createQuery(
      'SELECT per, proj
       FROM App\Entity\Personnel per
       LEFT JOIN per.project proj
       WHERE per.is_deleted = 0'
    );

    return $query->getResult();
  }

  public function createJoinedQueryBuilder(
    string $personnel = "personnel",
    string $project = "project",
    string $account = "account",
  ) {
    $qb = $this->createQueryBuilder($personnel)
      ->leftJoin("$personnel.project", $project)
      ->leftJoin('personnel.account', $account)
      ->select($personnel, $project, $account)
      ->where("$personnel.is_deleted = 0");

    return $qb;
  }
}
