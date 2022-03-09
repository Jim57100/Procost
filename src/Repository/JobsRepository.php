<?php

namespace App\Repository;

use App\Entity\Jobs;
use Doctrine\ORM\Query;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Jobs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jobs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jobs[]    findAll()
 * @method Jobs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jobs::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Jobs $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Jobs $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findAllWithPagination() :Query
    {
        return $this->createQueryBuilder('j')
        ->getQuery();
    }

    public function findOneWithEmployees(int $id): bool
    {
        $qb = $this->createQueryBuilder('j')
            ->where('j.id = :id')
            ->setParameter('id', $id);

        $this->addJoinEmployees($qb);

        if ($qb->getQuery()->getOneOrNullResult() === null) {
            dd(false);
            return false;
        } else {
            dd(true);
            return true;
        }
        
    }

    private function addJoinEmployees(QueryBuilder $qb): void
    {
        $qb
            ->addSelect('e')
            ->innerJoin('e.jobs', 'e')
        ;
    }
    // /**
    //  * @return Jobs[] Returns an array of Jobs objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Jobs
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
