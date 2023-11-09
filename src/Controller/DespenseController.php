<?php

namespace App\Controller;

use App\Entity\Despense;
use App\Form\DespenseType;
use App\Repository\DespenseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/despense')]
class DespenseController extends AbstractController
{
    #[Route('/', name: 'app_despense_index', methods: ['GET'])]
    public function index(DespenseRepository $despenseRepository): Response
    {
        return $this->render('despense/index.html.twig', [
            'despenses' => $despenseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_despense_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DespenseRepository $despenseRepository): Response
    {
        $despense = new Despense();
        $form = $this->createForm(DespenseType::class, $despense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $despenseRepository->save($despense, true);

            return $this->redirectToRoute('app_despense_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('despense/new.html.twig', [
            'despense' => $despense,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_despense_show', methods: ['GET'])]
    public function show(Despense $despense): Response
    {
        return $this->render('despense/show.html.twig', [
            'despense' => $despense,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_despense_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Despense $despense, DespenseRepository $despenseRepository): Response
    {
        $form = $this->createForm(DespenseType::class, $despense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $despenseRepository->save($despense, true);

            return $this->redirectToRoute('app_despense_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('despense/edit.html.twig', [
            'despense' => $despense,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_despense_delete', methods: ['POST'])]
    public function delete(Request $request, Despense $despense, DespenseRepository $despenseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$despense->getId(), $request->request->get('_token'))) {
            $despenseRepository->remove($despense, true);
        }

        return $this->redirectToRoute('app_despense_index', [], Response::HTTP_SEE_OTHER);
    }
}
