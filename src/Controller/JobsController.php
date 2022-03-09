<?php

namespace App\Controller;

use App\Entity\Jobs;
use App\Form\JobsType;
use App\Repository\JobsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class JobsController extends AbstractController
{

    #[Route('/jobs', name: 'jobs')]
    public function index(JobsRepository $repo, PaginatorInterface $paginatorInterface, Request $request): Response
    {
        
        $jobs = $paginatorInterface->paginate(
            $repo->findAllWithPagination(),
            $request->query->getInt('page', 1),
            10 /*limit per page*/
        );
        return $this->render('dashboard/jobs/jobs_list.html.twig', [
            'appTitle' => 'Métiers',
            'jobs' => $jobs
        ]);
    }

    #[Route('/jobs/edit/create', name: 'jobs_create', methods: ['GET', 'POST'])]
    #[Route('/jobs/edit/{id}', name: 'jobs_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function editOrAdd(Jobs $job = null, Request $request, EntityManagerInterface $em): Response
    {

        if (!$job) {
            $job = new Jobs();
        }

        $form = $this->createForm(JobsType::class, $job);
        $form->handleRequest($request);
        // dd($form);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($job);
            $em->flush();

            $this->addFlash('success', 'L\'envoi a bien été effectué');
            // return $this->redirectToRoute('jobs');
        }
        return $this->render('dashboard/jobs/jobs_editForm.html.twig', [
            'appTitle' => 'Edition d\'un métier',
            'job' => $job,
            'form' => $form->createView(),
            'isEdit' => $job->getId() !== null
        ]);
    }

    #[Route('/jobs/delete/{id}', name: 'jobs_delete', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function delete(Jobs $job, EntityManagerInterface $em, Request $request): Response
    {

        if (!$job) {
            $this->addFlash('warning', 'Le métier n\a pas été trouvé');
            return $this->redirectToRoute('jobs');
        }

        if ($this->isCsrfTokenValid("SUP" . $job->getId(), $request->get('_token'))) {
            $em->remove($job);
            $em->flush();
            $this->addFlash('success', 'Le métier a bien été supprimé');
            return $this->redirectToRoute('jobs');
        } 
    }
}
