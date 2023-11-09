<?php

namespace App\Controller;

use App\Entity\ActiviteCentre;
use App\Form\ActiviteCentreType;
use App\Repository\ActiviteCentreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/activitecentre')]
class ActiviteCentreController extends AbstractController
{
    #[Route('/', name: 'app_activite_centre_index', methods: ['GET'])]
    public function index(ActiviteCentreRepository $activiteCentreRepository): Response
    {
        return $this->render('activite_centre/index.html.twig', [
            'activite_centres' => $activiteCentreRepository->findAll(),
        ]);
    }


    #[Route('/front', name: 'app_activite_centre_index2', methods: ['GET'])]
    public function index2(ActiviteCentreRepository $activiteCentreRepository): Response
    {
        return $this->render('activite_centre/ActiviteFront.html.twig', [
            'activite_centres' => $activiteCentreRepository->findAll(),
        ]);
    }




    #[Route('/new', name: 'app_activite_centre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ActiviteCentreRepository $activiteCentreRepository): Response
    {
        $activiteCentre = new ActiviteCentre();
        $form = $this->createForm(ActiviteCentreType::class, $activiteCentre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activiteCentreRepository->save($activiteCentre, true);

            return $this->redirectToRoute('app_activite_centre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activite_centre/new.html.twig', [
            'activite_centre' => $activiteCentre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_activite_centre_show', methods: ['GET'])]
    public function show(ActiviteCentre $activiteCentre): Response
    {
        return $this->render('activite_centre/show.html.twig', [
            'activite_centre' => $activiteCentre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_activite_centre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ActiviteCentre $activiteCentre, ActiviteCentreRepository $activiteCentreRepository): Response
    {
        $form = $this->createForm(ActiviteCentreType::class, $activiteCentre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activiteCentreRepository->save($activiteCentre, true);

            return $this->redirectToRoute('app_activite_centre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activite_centre/edit.html.twig', [
            'activite_centre' => $activiteCentre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_activite_centre_delete', methods: ['POST'])]
    public function delete(Request $request, ActiviteCentre $activiteCentre, ActiviteCentreRepository $activiteCentreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activiteCentre->getId(), $request->request->get('_token'))) {
            $activiteCentreRepository->remove($activiteCentre, true);
        }

        return $this->redirectToRoute('app_activite_centre_index', [], Response::HTTP_SEE_OTHER);
    }
}
