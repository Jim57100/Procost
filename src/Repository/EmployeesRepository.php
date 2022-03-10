<?php

namespace App\Repository;

use Doctrine\ORM\Query;
use App\Entity\Employees;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Employees|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employees|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employees[]    findAll()
 * @method Employees[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employees::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Employees $entity, bool $flush = true): void
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
    public function remove(Employees $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
    
    public function findAllWithPagination() :Query
    {
        return $this->createQueryBuilder('e')
        ->getQuery();
    }

    //DO NOT WORK ???
    // public function findAllTimesById(int $id)
    // {
    //     return $this
    //         ->createQueryBuilder('e')
    //         ->addSelect('t')
    //         ->join('e.times', 't')
    //         ->where('t.id = $id')
    //         ->setParameter('id', $id)
    //         ->getQuery()
    //         ->getResult();
    // }



}
