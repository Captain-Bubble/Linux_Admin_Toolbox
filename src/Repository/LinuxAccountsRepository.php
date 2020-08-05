<?php

namespace App\Repository;

use App\Entity\LinuxAccounts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LinuxAccounts|null find($id, $lockMode = null, $lockVersion = null)
 * @method LinuxAccounts|null findOneBy(array $criteria, array $orderBy = null)
 * @method LinuxAccounts[]    findAll()
 * @method LinuxAccounts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinuxAccountsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LinuxAccounts::class);
    }

    // /**
    //  * @return LinuxAccounts[] Returns an array of LinuxAccounts objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LinuxAccounts
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
