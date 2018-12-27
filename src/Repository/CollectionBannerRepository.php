<?php

namespace App\Repository;

use App\Entity\CollectionBanner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CollectionBanner|null find($id, $lockMode = null, $lockVersion = null)
 * @method CollectionBanner|null findOneBy(array $criteria, array $orderBy = null)
 * @method CollectionBanner[]    findAll()
 * @method CollectionBanner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CollectionBannerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CollectionBanner::class);
    }

    // /**
    //  * @return CollectionBanner[] Returns an array of CollectionBanner objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CollectionBanner
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
