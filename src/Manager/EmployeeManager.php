<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Employees;
use App\Entity\Times;
use App\Repository\EmployeesRepository;
use Doctrine\ORM\EntityManagerInterface;

final class EmployeeManager
{
  public function __construct(
    private EntityManagerInterface $em,
  )
  {
    # code...
  }

  //THIS IS OK
  public function addTime(Employees $employees, Times $times) {
    $employees->addTime($times);

    $this->em->persist($times);
    $this->em->flush();
    
  }

    //DO NOT WORK ???
    // public function doMath(Employees $employees, EmployeesRepository $repo)
    // {
    //     $employeeCost = $employees->getCost();
    //     $sumTimes = array_sum($repo->findAllTimesById($employees->getId()));
    //     $totalCost = $employeeCost * $sumTimes;
        
    //     return $totalCost;
    // }


}