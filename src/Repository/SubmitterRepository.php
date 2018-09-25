<?php

namespace App\Repository;

use App\Entity\Submitter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Submitter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Submitter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Submitter[]    findAll()
 * @method Submitter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubmitterRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Submitter::class);
    }

    public function findByInstance($instance)
    {
        return $this->createQueryBuilder('s')
            ->join('s.events', 'e', 'WITH', 'e.instance = :instance')
            ->setParameter('instance', $instance)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Submitter[] Returns an array of Submitter objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Submitter
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
