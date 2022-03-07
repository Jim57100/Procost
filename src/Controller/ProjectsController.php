<?php

namespace App\Controller;

use App\Repository\ProjectsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/projects/detail', name: 'project_detail')]
    public function detail(): Response
    {
        return $this->render('dashboard/projects/projects_detail.html.twig', [
            'appTitle' => 'Details',
        ]);
    }

    #[Route('/projects/edit', name: 'project_edit')]
    public function editProject(): Response
    {
        return $this->render('dashboard/projects/projects_form.html.twig', [
            'appTitle' => 'Edition',
        ]);
    }
}
