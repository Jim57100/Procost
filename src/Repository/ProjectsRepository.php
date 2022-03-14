<?php

namespace App\Repository;

use Doctrine\ORM\Query;
use App\Entity\Projects;
use App\Entity\Employees;
use App\Entity\Times;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Projects|null find($id, $lockMode = null, $lockVersion = null)
 * @method Projects|null findOneBy(array $criteria, array $orderBy = null)
 * @method Projects[]    findAll()
 * @method Projects[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Projects::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Projects $entity, bool $flush = true): void
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
    public function remove(Projects $entity, bool $flush = true): void
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
    public function findAllWithPagination(): Query
    {
        return $this
            ->createQueryBuilder('t')
            ->getQuery();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function getProjectsInProgress(): array
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id) as projects')
            ->where("p.deliveryDate IS NULL ")
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function getFinishedProjects(): array
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id) as projects')
            ->where("p.deliveryDate IS NOT NULL ")
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function findLastProjects(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.name, p.startDate, p.salePrice, p.deliveryDate, SUM(employees.cost * times.times) as cost')
            ->join(Employees::class, "employees")
            ->join(Times::class, "times")
            ->where('p.id = times.projects')
            ->andWhere("employees.id = times.employees")
            ->groupBy('p.name')
            ->orderBy('p.startDate', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function doMathOnAllSalePrices(): int
    {
        return $this->createQueryBuilder('p')
            ->select("SUM(p.salePrice) as sale")
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function doMathOnTimeCostForSoldedProjects(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.name, p.startDate, p.salePrice, p.deliveryDate, SUM(employees.cost * times.times) as salePrice')
            ->join(Employees::class, "employees")
            ->join(Times::class, "times")
            ->where('p.id = times.projects')
            ->andWhere("employees.id = times.employees")
            ->andWhere('p.deliveryDate IS NOT NULL')
            ->groupBy('p.name')
            ->orderBy('p.startDate', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }
}
