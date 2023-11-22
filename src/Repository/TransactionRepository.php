<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 *
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function findAllForAccount(Account $account, int $page, int $limit)
    {
        $firstResult = $page === 1 ? 0 : ($page-1)*$limit;

        $qb = $this->createQueryBuilder('t');
        $result = $qb->where('t.accountFrom = :id')
            ->setParameter('id', $account->getId())
            ->orWhere('t.accountTo = :id')
            ->setParameter('id', $account->getId())
            ->setFirstResult($firstResult)
            ->setMaxResults($limit)
            ->orderBy('t.id', 'DESC')
            ->getQuery()
            ->getResult();
        return $result;
    }
}
