<?php

namespace App\Controller;

use App\Entity\Centre;
use App\Entity\PlanningCentre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcccController extends AbstractController
{
    #[Route('/accc', name: 'app_accc')]
    public function index(): Response
    {
        return $this->render('accc/index.html.twig', [
            'controller_name' => 'AcccController',
        ]);
    }
    #[Route('/{id}/activite', name: 'app_planning_centre_show2', methods: ['GET'])]
    public function show2(PlanningCentre $planningCentre): Response
    {
        return $this->render('planning_centre/test2.html.twig', [
            'planning_centre' => $planningCentre,
        ]);
    }
}
