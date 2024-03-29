<?php

namespace App\Repository;

use App\Entity\Achievement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Achievement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Achievement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Achievement[]    findAll()
 * @method Achievement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AchievementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Achievement::class);
    }

    public function findAchievementByAchievementUser($id)
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.achievementUsers', 'au', 'a.id = au.achievement')
            ->where('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Achievement[] Returns an array of Achievement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Achievement
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
