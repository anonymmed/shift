<?php

namespace App\Repository;

use App\Entity\Questions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Questions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Questions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Questions[]    findAll()
 * @method Questions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Questions::class);
    }

    function getDimensions() {
        return $this->createQueryBuilder('q')
            ->select('q.dimension')
            ->groupBy('q.dimension')
            ->orderBy('q.id', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
    function getDimensionCount(string $dimension) {
        return $this->createQueryBuilder('q')
            ->select('count(q.id)')
            ->where('d.dimension = :dimension')
            ->setParameter('dimension', $dimension)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getAllQuestions() {
        return $this->createQueryBuilder('q')
            ->select('q.id', 'q.name')
            ->orderBy('q.id', 'ASC')
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);
    }

    // /**
    //  * @return Questions[] Returns an array of Questions objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Questions
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
