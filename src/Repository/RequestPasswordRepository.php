<?php

namespace App\Repository;

use App\Entity\RequestPassword;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RequestPassword|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequestPassword|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequestPassword[]    findAll()
 * @method RequestPassword[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestPasswordRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RequestPassword::class);
    }

    /**
     * @param $code
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByValidCodeRequest($code)
    {
        return $this->createQueryBuilder('c')
            ->where('c.code = :val')
            ->setParameter('val', $code)
            ->orderBy('c.created', 'DESC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?RequestPassword
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
