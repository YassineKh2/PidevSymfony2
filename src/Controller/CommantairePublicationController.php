<?php

namespace App\Controller;

use App\Entity\CommantairePublication;
use App\Entity\Publication;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\CommantairePublicationType;
use App\Repository\CommantairePublicationRepository;
use App\Repository\PublicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\BadWordsService;



#[Route('/commantaire/publication')]
class CommantairePublicationController extends AbstractController
{
    #[Route('/', name: 'app_commantaire_publication_index', methods: ['GET'])]
    public function index(CommantairePublicationRepository $commantairePublicationRepository): Response
    {
        return $this->render('commantaire_publication/index.html.twig', [
            'commantaire_publications' => $commantairePublicationRepository->findAll(),
        ]);
    } 
    
    #[Route('/{id}', name: 'showcommentsforonepub', methods: ['GET'])]
    public function showcommm(CommantairePublicationRepository $commantairePublicationRepository,$id): Response
    {
        $pub=$this->getDoctrine()->getRepository(CommantairePublication::class)->findBy(['Publication'=>$id]);
        return $this->render('commantaire_publication/index.html.twig', [
            'commantaire_publications' => $commantairePublicationRepository->findBy(['id'=>$pub]),
        ]);
    }


    #[Route('/{id}/new', name: 'app_commantaire_publication_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommantairePublicationRepository $commantairePublicationRepository,UserRepository $userRepository ,PublicationRepository $publicationRepository, BadWordsService $badWordsService, $id): Response
    {
        $publication = new Publication();
        $token = $this->get('security.token_storage')->getToken();
        if ($token) {
            $user = $token->getUser();
            if ($user) {
                $userId = $user->getId();
            }
        }
        $user = $userRepository->find($userId);

        $publication = $publicationRepository->find($id);
        $commantairePublication = new CommantairePublication();
        $commantairePublication->setUser($user);
        $commantairePublication->setDateCommantaire(new \DateTime("now"));
        $commantairePublication->setPublication($publication);
        $form = $this->createForm(CommantairePublicationType::class, $commantairePublication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if the comment contains any bad words
            $commentText = $commantairePublication->getContenuCommantaire();
            $containsBadWords = $badWordsService->containsBadWords($commentText);

            if ($containsBadWords) {
                // If the comment contains bad words, show an error message
                $this->addFlash('error', 'Your comment contains bad words!');
            } else {
                // If the comment does not contain bad words, save it to the database
                $commantairePublicationRepository->save($commantairePublication, true);
                return $this->redirectToRoute('app_commantaire_publication_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('commantaire_publication/new.html.twig', [
            'commantaire_publication' => $commantairePublication,
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'app_commantaire_publication_show', methods: ['GET'])]
    public function show(CommantairePublication $commantairePublication): Response
    {
        
        return $this->render('commantaire_publication/show.html.twig', [
            'commantaire_publication' => $commantairePublication,
        ]);
    }
    
    #[Route('/{id}', name: 'app_commantaire_publication', methods: ['GET'])]
    public function showComments(CommantairePublication $commantairePublication): Response
    {
        return $this->render('commantaire_publication/show.html.twig', [
            'commantaire_publication' => $commantairePublication,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commantaire_publication_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CommantairePublication $commantairePublication, CommantairePublicationRepository $commantairePublicationRepository): Response
    {
        $form = $this->createForm(CommantairePublicationType::class, $commantairePublication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commantairePublicationRepository->save($commantairePublication, true);

            return $this->redirectToRoute('app_commantaire_publication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commantaire_publication/edit.html.twig', [
            'commantaire_publication' => $commantairePublication,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commantaire_publication_delete', methods: ['POST'])]
    public function delete(Request $request, CommantairePublication $commantairePublication, CommantairePublicationRepository $commantairePublicationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commantairePublication->getId(), $request->request->get('_token'))) {
            $commantairePublicationRepository->remove($commantairePublication, true);
        }

        return $this->redirectToRoute('app_commantaire_publication_index', [], Response::HTTP_SEE_OTHER);
    }
}