<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Project::class);
  }
  public function findAllJoined()
  {
    $entityManager = $this->getEntityManager();
    $query = $entityManager->createQuery(
      'SELECT proj, pa
            FROM App\Entity\Project proj
            LEFT JOIN proj.focal_person pa
            WHERE proj.is_deleted = 0'
    );

    return $query->getArrayResult();
  }

  public function createJoinedQueryBuilder(

    string $project = "project",
    string $focal_person = "focal_person",
  ): QueryBuilder {
    $qb = $this->createQueryBuilder($project)
      ->leftJoin("$project.focal_person", $focal_person)
      ->select($project, $focal_person);

    return $qb;
  }

  //    /**
  //     * @return Project[] Returns an array of Project objects
  //     */
  //    public function findByExampleField($value): array
  //    {
  //        return $this->createQueryBuilder('p')
  //            ->andWhere('p.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->orderBy('p.id', 'ASC')
  //            ->setMaxResults(10)
  //            ->getQuery()
  //            ->getResult()
  //        ;
  //    }

  //    public function findOneBySomeField($value): ?Project
  //    {
  //        return $this->createQueryBuilder('p')
  //            ->andWhere('p.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
