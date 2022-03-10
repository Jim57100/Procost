<?php

namespace App\Controller;

use App\Entity\Projects;
use App\Form\ProjectsType;
use App\Repository\TimesRepository;
use App\Repository\ProjectsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProjectsController extends AbstractController
{
    #[Route('/projects', name: 'projects')]
    public function index(ProjectsRepository $repo, PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $projects = $paginatorInterface->paginate(
            $repo->findAllWithPagination(), 
            $request->query->getInt('page', 1), 
            10 /*limit per page*/
        );
        return $this->render('dashboard/projects/projects_list.html.twig', [
            'appTitle' => 'Projets',
            'projects' => $projects
        ]);
    }

    #[Route('/projects/detail/{id}', name: 'project_detail')]
    public function detail(Projects $project, TimesRepository $repo, PaginatorInterface $paginatorInterface, Request $request): Response
    {


        $times = $paginatorInterface->paginate(
            $repo->findAllWithPagination(), 
            $request->query->getInt('page', 1), 
            10 /*limit per page*/
        );

        return $this->render('dashboard/projects/projects_detail.html.twig', [
            'appTitle' => 'Details',
            'project' => $project,
            'times' => $times
           
        ]);
    }


    #[Route('/projects/edit/create', name: 'project_create', methods: ['GET', 'POST'])]
    #[Route('/projects/edit/{id}', name: 'project_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function editProject(Projects $projects = null, Request $request, EntityManagerInterface $em): Response
    {
        if(!$projects) {
            $projects = new Projects();
        }

        $form = $this->createForm(ProjectsType::class, $projects);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->persist($projects);
            $em->flush();

            $this->addFlash('success', 'L\'envoi a bien été effectué');
        
        }
        return $this->render('dashboard/projects/projects_form.html.twig', [
            'appTitle' => 'Edition',
            'projects' => $projects,
            'form' => $form->createView(),
            'isEdit' => $projects->getId() !== null

        ]);
    }

    #[Route('/project/delete/{id}', name: 'project_delete', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function delete(Projects $projects, EntityManagerInterface $em, Request $request): Response
    {

        if ($projects === null) {
            throw new NotFoundHttpException();
        }

        if ($this->isCsrfTokenValid("SUP" . $projects->getId(), $request->get('_token'))) {
            $em->remove($projects);
            $em->flush();
            return $this->redirectToRoute('projects');
        } 
    }

    // #[Route('/project/delivery/{id}', name: 'project_delivery', methods: ['POST'], requirements: ['id' => '\d+'])]
    // public function delivery(Projects $projects, EntityManagerInterface $em, Request $request): Response
    // {


    //     $em->setDeliveryDate(new DateTime());
    //     return $em->flush();

    // }

}
