<?php

namespace App\Repository;

use App\Entity\CollectionItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CollectionItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method CollectionItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method CollectionItem[]    findAll()
 * @method CollectionItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CollectionItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CollectionItem::class);
    }

    // /**
    //  * @return CollectionItem[] Returns an array of CollectionItem objects
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
    public function findOneBySomeField($value): ?CollectionItem
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
