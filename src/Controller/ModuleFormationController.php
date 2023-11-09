<?php

namespace App\Controller;

use App\Entity\ModuleFormation;
use App\Form\ModuleFormationType;
use App\Repository\ModuleFormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/module/formation')]
class ModuleFormationController extends AbstractController
{
    #[Route('/', name: 'app_module_formation_index', methods: ['GET'])]
    public function index(ModuleFormationRepository $moduleFormationRepository): Response
    {
        return $this->render('module_formation/index.html.twig', [
            'module_formations' => $moduleFormationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_module_formation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ModuleFormationRepository $moduleFormationRepository): Response
    {
        $moduleFormation = new ModuleFormation();
        $form = $this->createForm(ModuleFormationType::class, $moduleFormation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $moduleFormationRepository->save($moduleFormation, true);

            return $this->redirectToRoute('app_module_formation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('module_formation/new.html.twig', [
            'module_formation' => $moduleFormation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_module_formation_show', methods: ['GET'])]
    public function show(ModuleFormation $moduleFormation): Response
    {
        return $this->render('module_formation/show.html.twig', [
            'module_formation' => $moduleFormation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_module_formation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ModuleFormation $moduleFormation, ModuleFormationRepository $moduleFormationRepository): Response
    {
        $form = $this->createForm(ModuleFormationType::class, $moduleFormation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $moduleFormationRepository->save($moduleFormation, true);

            return $this->redirectToRoute('app_module_formation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('module_formation/edit.html.twig', [
            'module_formation' => $moduleFormation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_module_formation_delete', methods: ['POST'])]
    public function delete(Request $request, ModuleFormation $moduleFormation, ModuleFormationRepository $moduleFormationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$moduleFormation->getId(), $request->request->get('_token'))) {
            $moduleFormationRepository->remove($moduleFormation, true);
        }

        return $this->redirectToRoute('app_module_formation_index', [], Response::HTTP_SEE_OTHER);
    }
}
