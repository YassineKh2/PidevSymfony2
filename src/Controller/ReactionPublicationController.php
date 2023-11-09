<?php

namespace App\Controller;

use App\Entity\ReactionPublication;
use App\Form\ReactionPublicationType;
use App\Repository\ReactionPublicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reaction/publication')]
class ReactionPublicationController extends AbstractController
{
    #[Route('/', name: 'app_reaction_publication_index', methods: ['GET'])]
    public function index(ReactionPublicationRepository $reactionPublicationRepository): Response
    {
        return $this->render('reaction_publication/index.html.twig', [
            'reaction_publications' => $reactionPublicationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reaction_publication_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReactionPublicationRepository $reactionPublicationRepository): Response
    {
        $reactionPublication = new ReactionPublication();
        $form = $this->createForm(ReactionPublicationType::class, $reactionPublication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reactionPublicationRepository->save($reactionPublication, true);

            return $this->redirectToRoute('app_reaction_publication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reaction_publication/new.html.twig', [
            'reaction_publication' => $reactionPublication,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reaction_publication_show', methods: ['GET'])]
    public function show(ReactionPublication $reactionPublication): Response
    {
        return $this->render('reaction_publication/show.html.twig', [
            'reaction_publication' => $reactionPublication,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reaction_publication_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReactionPublication $reactionPublication, ReactionPublicationRepository $reactionPublicationRepository): Response
    {
        $form = $this->createForm(ReactionPublicationType::class, $reactionPublication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reactionPublicationRepository->save($reactionPublication, true);

            return $this->redirectToRoute('app_reaction_publication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reaction_publication/edit.html.twig', [
            'reaction_publication' => $reactionPublication,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reaction_publication_delete', methods: ['POST'])]
    public function delete(Request $request, ReactionPublication $reactionPublication, ReactionPublicationRepository $reactionPublicationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reactionPublication->getId(), $request->request->get('_token'))) {
            $reactionPublicationRepository->remove($reactionPublication, true);
        }

        return $this->redirectToRoute('app_reaction_publication_index', [], Response::HTTP_SEE_OTHER);
    }
}
