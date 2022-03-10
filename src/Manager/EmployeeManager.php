<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Employees;
use App\Entity\Times;
use Doctrine\ORM\EntityManagerInterface;

final class EmployeeManager
{
  public function __construct(
    private EntityManagerInterface $em,
  )
  {
    # code...
  }

  public function addTime(Employees $employees, Times $times) {
    $employees->addTime($times);

    $this->em->persist($times);
    $this->em->flush();
    
  }

}