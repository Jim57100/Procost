<?php

namespace App\Controller;

use App\Repository\EmployeesRepository;
use App\Repository\ProjectsRepository;
use App\Repository\TimesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    public function __construct(
        private ProjectsRepository $pr,
        private EmployeesRepository $er,
        private TimesRepository $tr
    )
    {}


    #[Route('/', name: 'dashboard')]
    public function index(): Response
    {
        $nbProjectsInProgress = $this->pr->getProjectsInProgress();
        $nbFinishedProjects = $this->pr->getFinishedProjects();
        $LastProjects = $this->pr->findLastProjects();
        $totalSellingPrices = $this->pr->doMathOnAllSalePrices();
        $totalTimesCost = $this->pr->doMathOnTimeCostForSoldedProjects();
        $lastTimes = $this->er->getLastTimes();
        $bestEmployee = $this->er->bestEmployee();
        $nbEmployees = $this->er->countEmployees();
        $productionTime = $this->tr->countAllProductionTime();


        return $this->render('dashboard/index.html.twig', [
            'appTitle'           => 'dashboard',
            'nbInProgress'       => $nbProjectsInProgress,
            'nbFinishedProjects' => $nbFinishedProjects,
            'lastProjects'       => $LastProjects,
            'totalSellingPrices' => $totalSellingPrices,
            'totalTimesCost'     => $totalTimesCost,
            'lastTimes'          => $lastTimes,
            'nbEmployees'        => $nbEmployees,
            'bestEmployee'       => $bestEmployee,
            'productionTime'     => $productionTime
        ]);
    }
}
