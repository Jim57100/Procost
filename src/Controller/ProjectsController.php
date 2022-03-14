<?php

namespace App\Controller;

use DateTime;
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
    public function __construct(
        private ProjectsRepository $repo,
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('/projects', name: 'projects')]
    public function index(PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $projects = $paginatorInterface->paginate(
            $this->repo->findAllWithPagination(),
            $request->query->getInt('page', 1),
            10 /*limit per page*/
        );
        return $this->render('dashboard/projects/projects_list.html.twig', [
            'appTitle' => 'Projets',
            'projects' => $projects
        ]);
    }


    #[Route('/projects/detail/{id}', name: 'project_detail')]
    public function detail(Projects $project, TimesRepository $timesRepo, PaginatorInterface $paginatorInterface, Request $request): Response
    {

        //Simple pagination
        $times = $paginatorInterface->paginate(
            $timesRepo->findAllWithPagination(),
            $request->query->getInt('page', 1),
            10 /*limit per page*/
        );

        return $this->render('dashboard/projects/projects_detail.html.twig', [
            'appTitle' => 'Details',
            'project'  => $project,
            'times'    => $times,

        ]);
    }


    #[Route('/projects/edit/create', name: 'project_create', methods: ['GET', 'POST'])]
    #[Route('/projects/edit/{id}', name: 'project_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function editProject(Projects $projects = null, Request $request): Response
    {
        if (!$projects) {
            $projects = new Projects();
        }

        $form = $this->createForm(ProjectsType::class, $projects);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($projects);
            $this->em->flush();

            $this->addFlash('success', 'L\'envoi a bien été effectué');
        }
        return $this->render('dashboard/projects/projects_form.html.twig', [
            'appTitle' => 'Edition',
            'projects' => $projects,
            'form'     => $form->createView(),
            'isEdit'   => $projects->getId() !== null

        ]);
    }


    #[Route('/project/delete/{id}', name: 'project_delete', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function delete(Projects $projects, Request $request): Response
    {
        if ($projects === null) {
            throw new NotFoundHttpException();
        }

        if ($this->isCsrfTokenValid("SUP" . $projects->getId(), $request->get('_token'))) {
            $this->em->remove($projects);
            $this->em->flush();
            return $this->redirectToRoute('projects');
        }
    }


    #[Route('/project/delivery/{id}', name: 'project_delivery', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function markAsDelivered(Projects $projects, Request $request): Response
    {
        if ($projects === null) {
            throw new NotFoundHttpException();
        }
        $request->get('_delivery');
        $projects->setDeliveryDate(new DateTime());
        $this->em->persist($projects);
        $this->em->flush();
        // TO DO :   cout établi,
        return $this->redirectToRoute('projects');
    }

}
