<?php

namespace App\Repository;

use App\Entity\LinuxServer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LinuxServer|null find($id, $lockMode = null, $lockVersion = null)
 * @method LinuxServer|null findOneBy(array $criteria, array $orderBy = null)
 * @method LinuxServer[]    findAll()
 * @method LinuxServer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinuxServerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LinuxServer::class);
    }

    // /**
    //  * @return LinuxServer[] Returns an array of LinuxServer objects
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
    public function findOneBySomeField($value): ?LinuxServer
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
