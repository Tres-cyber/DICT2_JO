<?php

namespace App\Repository;

use App\Entity\JobOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function findAllJoinedToPersonnel(){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT jo, pi, pp ,pa
            FROM App\Entity\JobOrders jo
            LEFT JOIN jo.issued_by pi,
            LEFT JOIN jo.approved_by pa,
            LEFT JOIN jo.performer_id pp
            '
        );
    }

//    /**
//     * @return JobOrder[] Returns an array of JobOrder objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('j.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?JobOrder
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
