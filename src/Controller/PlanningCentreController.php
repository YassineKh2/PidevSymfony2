<?php

namespace App\Controller;

use App\Entity\Centre;
use App\Entity\PlanningCentre;
use App\Form\PlanningCentreType;
use App\Repository\PlanningCentreRepository;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/planningcentre')]
class PlanningCentreController extends AbstractController
{
    #[Route('/', name: 'app_planning_centre_index', methods: ['GET'])]
    public function index(PlanningCentreRepository $planningCentreRepository): Response
    {
        return $this->render('planning_centre/index.html.twig', [
            'planning_centres' => $planningCentreRepository->findAll(),
        ]);
    }
    #[Route('/front', name: 'app_planning_centre_index2', methods: ['GET'])]
    public function index2(PlanningCentreRepository $planningCentreRepository): Response
    {
        return $this->render('planning_centre/PlanningFront.html.twig', [
            'planning_centres' => $planningCentreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_planning_centre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PlanningCentreRepository $planningCentreRepository): Response
    {
        $planningCentre = new PlanningCentre();
        $form = $this->createForm(PlanningCentreType::class, $planningCentre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $planningCentreRepository->save($planningCentre, true);

            return $this->redirectToRoute('app_planning_centre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('planning_centre/new.html.twig', [
            'planning_centre' => $planningCentre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_planning_centre_show', methods: ['GET'])]
    public function show(PlanningCentre $planningCentre,Centre $centre): Response
    {
        return $this->render('planning_centre/show.html.twig', [
            'planning_centre' => $planningCentre,
            'centre'=>$centre,
        ]);
    }

    #[Route('/{id}/pdf', name: 'app_pdf')]
    public function generatePdfAction($id)
    {
        $planning_centre = $this->getDoctrine()->getRepository(PlanningCentre::class)->find($id);
        $centre = $this->getDoctrine()->getRepository('App\Entity\Centre')->findOneBy(['id' => $planning_centre->getCentre()]);

        $planning_centre->setCentreNom($centre ? $centre->getNomCentre() : null);
        $planning_centre->setCentreLocale($centre ? $centre->getLocalisation() : null);




        $html = $this->renderView('planning_centre/print.html.twig', [
            'planning_centre' => $planning_centre,
        ]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdf = $dompdf->output();

        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="Planning n°'.$planning_centre->getId().'.pdf"',
        ]);
    }
    #[Route('/{id}/edit', name: 'app_planning_centre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PlanningCentre $planningCentre, PlanningCentreRepository $planningCentreRepository): Response
    {
        $form = $this->createForm(PlanningCentreType::class, $planningCentre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $planningCentreRepository->save($planningCentre, true);

            return $this->redirectToRoute('app_planning_centre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('planning_centre/edit.html.twig', [
            'planning_centre' => $planningCentre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_planning_centre_delete', methods: ['POST'])]
    public function delete(Request $request, PlanningCentre $planningCentre, PlanningCentreRepository $planningCentreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$planningCentre->getId(), $request->request->get('_token'))) {
            $planningCentreRepository->remove($planningCentre, true);
        }

        return $this->redirectToRoute('app_planning_centre_index', [], Response::HTTP_SEE_OTHER);
    }
}
