<?php

namespace App\Repository;

use App\Entity\Times;
use Doctrine\ORM\Query;
use App\Entity\Projects;
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
    
    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function findAllWithPagination() :Query
    {
        return $this->createQueryBuilder('e')
        ->getQuery();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function countEmployees() :array
    {
        return $this->createQueryBuilder('e')
            ->select('COUNT(e.id) as count')
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function getLastTimes() :array
    {
        return $this->createQueryBuilder('e')
            ->select("e.id, e.firstName, e.lastName, projects.id as projectsId, projects.name, projects.startDate, times.times")
            ->join(Projects::class, 'projects')
            ->join(Times::class, 'times')
            ->join(Employees::class, 'employees')
            ->where("employees.id = times.employees")
            ->andWhere('e.id = times.employees')
            ->andWhere('times.projects = projects.id')
            ->orderBy('times.id', 'DESC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult();
        
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function bestEmployee()
    {
        return $this->createQueryBuilder('e')
            ->select("e.id, e.firstName, e.lastName, e.hireDate, MAX(e.cost * times.times) as maxCost")
            ->join(Times::class, 'times')
            ->where("e.id = times.employees ")
            ->orderBy('maxCost', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
