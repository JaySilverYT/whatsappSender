<?php

namespace App\Repository;

use App\Entity\MassiveMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MassiveMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method MassiveMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method MassiveMessage[]    findAll()
 * @method MassiveMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MassiveMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MassiveMessage::class);
    }

    // /**
    //  * @return MassiveMessage[] Returns an array of MassiveMessage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MassiveMessage
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
