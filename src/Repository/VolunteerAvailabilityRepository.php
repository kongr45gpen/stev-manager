<?php

namespace App\Repository;

use App\Entity\VolunteerAvailability;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method VolunteerAvailability|null find($id, $lockMode = null, $lockVersion = null)
 * @method VolunteerAvailability|null findOneBy(array $criteria, array $orderBy = null)
 * @method VolunteerAvailability[]    findAll()
 * @method VolunteerAvailability[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VolunteerAvailabilityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VolunteerAvailability::class);
    }

//    /**
//     * @return VolunteerAvailability[] Returns an array of VolunteerAvailability objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VolunteerAvailability
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
