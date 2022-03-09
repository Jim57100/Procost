<?php

namespace App\Controller;

use App\Entity\Times;
use App\Form\TimesType;
use App\Entity\Employees;
use Doctrine\ORM\EntityManager;
use App\Repository\EmployeesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmployeesController extends AbstractController
{
    #[Route('/employees', name: 'employees')]
    public function index(EmployeesRepository $repo, PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $employees = $paginatorInterface->paginate(
            $repo->findAllWithPagination(), 
            $request->query->getInt('page', 1), 
            10 /*limit per page*/
        );
        return $this->render('dashboard/employees/employees_list.html.twig', [
            'appTitle' => 'Employés',
            'employees' => $employees
        ]);
    }

    #[Route('/employees/detail/{id}', name: 'employees_detail')]
    public function detail(Employees $employee, Request $request, EntityManagerInterface $em): Response
    {   
        $times = new Times();
        $form = $this->createForm(TimesType::class, $times);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($times);
            $em->flush();
            $this->addFlash('success', 'Votre temps est ajouté avec succès !');
        }

        return $this->render('dashboard/employees/employees_detail.html.twig', [
            'appTitle' => 'Employés détails',
            'employee' => $employee
        ]);
    }

    #[Route('/employees/edit', name: 'employees_edit')]
    public function edit(): Response
    {
        return $this->render('dashboard/employees/employees_form.html.twig', [
            'appTitle' => 'Employés édition',
        ]);
    }

    public function addTimes()
    {
        
    }

}
