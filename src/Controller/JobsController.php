<?php

namespace App\Controller;

use App\Entity\Jobs;
use App\Form\JobsType;
use App\Repository\JobsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
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
        if(!$job) {
            $job = new Jobs();
        }

        $form = $this->createForm(JobsType::class, $job);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            
            $job = $form->getData();
            dd($job);
            $em->persist($job);
            $em->flush();

            $this->addFlash('success', 'L\'envoi a bien été effectué');
            $this->redirectToRoute('jobs');
        
        } else {
            $this->addFlash('danger', 'Les données n\'ont pas été envoyées !');
        }
        return $this->render('dashboard/jobs/jobs_editForm.html.twig', [
            'appTitle' => 'Edition d\'un métier',
            'job' => $job,
            'form' => $form->createView(),
            'isEdit' => $job->getId() !== null
        ]);
    }
    
    #[Route('/jobs/edit/{id}', name: 'jobs_delete' , methods:"SUP", requirements: ['id' => '\d+'] )]
    public function delete(Jobs $job, EntityManagerInterface $em, Request $request) {
        if($this->isCsrfTokenValid("SUP" . $job->getId(), $request->get('_token'))) {
            $em->remove($job);
            $em->flush();
        }
    }

}
