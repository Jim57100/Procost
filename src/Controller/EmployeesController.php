<?php

namespace App\Controller;

use App\Entity\Times;
use App\Form\TimesType;
use App\Entity\Employees;
use App\Form\EmployeeType;
use Doctrine\ORM\EntityManager;
use App\Manager\EmployeeManager;
use App\Repository\EmployeesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EmployeesController extends AbstractController
{
    public function __construct(
        private EmployeeManager $employeeManager,
        private EntityManagerInterface $em,
        private EmployeesRepository $repo
    ){}

    #[Route('/employees', name: 'employees')]
    public function index(PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $employees = $paginatorInterface->paginate(
            $this->repo->findAllWithPagination(), 
            $request->query->getInt('page', 1), 
            10 /*limit per page*/
        );
        return $this->render('dashboard/employees/employees_list.html.twig', [
            'appTitle' => 'Employés',
            'employees' => $employees
        ]);
    }

    
    #[Route('/employees/detail/{id}', name: 'employees_detail')]
    public function detail(Employees $employee, Request $request): Response
    {   
        $times = new Times();

        $form = $this->createForm(TimesType::class, $times);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            
            $this->employeeManager->addTime($employee, $times);
            // $em->persist($times);
            // $em->flush();
            $this->addFlash('success', 'Votre temps est ajouté avec succès !');
        }

        return $this->render('dashboard/employees/employees_detail.html.twig', [
            'appTitle' => 'Employés détails',
            'form' => $form->createView(),
            'employee' => $employee
        ]);
    }


    #[Route('/employees/edit/create', name: 'employees_create', methods: ['GET', 'POST'])]
    #[Route('/employees/edit/{id}', name: 'employees_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function editOrAddEmployee(Employees $employee = null, Request $request, EntityManagerInterface $em): Response
    {
        if(!$employee) {
            $employee = new Employees();
        }  

        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->persist($employee);
            $em->flush();

            $this->addFlash('success', 'L\'envoi a bien été effectué');
        
        }

        return $this->render('dashboard/employees/employees_form.html.twig', [
            'appTitle' => 'Edition d\'un employé',
            'employee' => $employee,
            'form' => $form->createView(),
            'isEdit' => $employee->getId() !== null
        ]);
    }


    #[Route('/employees/delete/{id}', name: 'employees_delete', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function delete(Employees $employees, Request $request): Response
    {

        if ($employees === null) {
            throw new NotFoundHttpException();
        }

        if ($this->isCsrfTokenValid("SUP" . $employees->getId(), $request->get('_token'))) {
            $this->em->remove($employees);
            $this->em->flush();
            return $this->redirectToRoute('projects');
        } 
    }
}
