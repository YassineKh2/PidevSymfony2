<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Entity\CommantairePublication;
use App\Form\PublicationType;
use App\Repository\PublicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

#[Route('/admin/forum')]
class AdminPublicationController extends AbstractController
{
    #[Route('/', name: 'admin_publication_index', methods: ['GET'])]
    public function index(PublicationRepository $publicationRepository ): Response
    {
        
        return $this->render('publication/admin_index.html.twig', [
            'publications' => $publicationRepository->findAll(),
            
            
        ]);
    }

    #[Route('/{id}', name: 'admin_publication_show', methods: ['GET'])]
    public function show(Publication $publication): Response
    {
        return $this->render('publication/admin_show.html.twig', [
            'publication' => $publication,
        ]);
    }

    #[Route('/{id}', name: 'admin_publication_delete', methods: ['POST'])]
    public function delete(Request $request, Publication $publication, PublicationRepository $publicationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publication->getId(), $request->request->get('_token'))) {
            $publicationRepository->remove($publication, true);
        }

        return $this->redirectToRoute('admin_publication_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/approve', name: 'admin_publication_approve', methods: ['GET', 'POST'])]
    public function approve(Publication $publication, PublicationRepository $publicationRepository): Response
    {
        $publication->setIsApproved(true);
        $publicationRepository->save($publication, true);


        return $this->redirectToRoute('admin_publication_show', ['id' => $publication->getId()]);
    }

}