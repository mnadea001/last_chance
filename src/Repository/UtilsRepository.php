<?php

namespace App\Repository;

use App\Entity\Utils;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Utils|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utils|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utils[]    findAll()
 * @method Utils[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utils::class);
    }

    // /**
    //  * @return Utils[] Returns an array of Utils objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Utils
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
