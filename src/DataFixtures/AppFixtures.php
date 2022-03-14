<?php

namespace App\DataFixtures;

use App\Entity\Jobs;
use App\Entity\Employees;
use App\Entity\Projects;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $job1 = new Jobs();
        $job1->setLabel('Manager');
        $manager->persist($job1);

        $job2 = new Jobs();
        $job2->setLabel('Développeur Front');
        $manager->persist($job2);

        $job3 = new Jobs();
        $job3->setLabel('Designer');
        $manager->persist($job3);

        $job4 = new Jobs();
        $job4->setLabel('Développeur Back');
        $manager->persist($job4);

        for($i = 1; $i <= 12; $i++) {
            $employee = new Employees();
            $employee->setFirstName('Homer'.$i)
            ->setLastName('Simpson'.$i)
            ->setEmail('homer.simpson@procost.com')
            ->setJob($job1)
            ->setCost(mt_rand(100, 600))
            ->setHireDate(new DateTime());
            $manager->persist($employee);    
        }

        for($j = 1 ; $j <= 12; $j++) {
            $project = new Projects();
            $project -> setName('Projet'.$j)
            ->setDescription('une trèèèèèès loooongue description')
            ->setSalePrice(mt_rand(4000, 20000))
            ->setStartDate(new DateTime());
            $manager->persist($project); 
        }

        $manager->flush();
    }
    
}
