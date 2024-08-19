<?php

namespace App\Repository;

use App\Entity\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Account>
 */
class AccountRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Account::class);
  }

  /**
   * @return Account[] Returns an array of Account objects
   */
  public function findAllJoined(): array
  {

    $entityManager = $this->getEntityManager();
    $query = $entityManager->createQuery(
      'SELECT acc, per, sess
       FROM App\Entity\Account acc
       LEFT JOIN acc.personnel per
       LEFT JOIN acc.current_session sess
       WHERE per IS NULL OR per.is_deleted = 0'
    );
    return $query->getResult();
  }

  public function createJoinedQueryBuilder(
    string $account = 'account',
    string $personnel = 'personnel',
    string $current_session = 'current_session'
  ): QueryBuilder {
    return $this->createQueryBuilder($account)
      ->leftJoin("$account.personnel", $personnel)
      ->leftJoin("$account.current_session", $current_session)
      ->where("$personnel IS NULL OR $personnel.is_deleted = 0")
      ->select($account, $personnel, $current_session);
  }
}
