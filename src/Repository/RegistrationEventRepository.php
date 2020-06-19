<?php

namespace App\Repository;

use App\Entity\RegistrationEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RegistrationEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method RegistrationEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method RegistrationEvent[]    findAll()
 * @method RegistrationEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegistrationEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegistrationEvent::class);
    }

    // /**
    //  * @return RegistrationEvent[] Returns an array of RegistrationEvent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RegistrationEvent
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
