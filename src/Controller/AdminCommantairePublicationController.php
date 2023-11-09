<?php

namespace App\Controller;

use App\Entity\CommantairePublication;
use App\Entity\Publication;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\CommantairePublicationType;
use App\Repository\CommantairePublicationRepository;
use App\Repository\PublicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;



#[Route('/admin/commantaire/publication')]
class AdminCommantairePublicationController extends AbstractController
{
    #[Route('/', name: 'admin_commantaire_publication_index', methods: ['GET'])]
    public function index(CommantairePublicationRepository $commantairePublicationRepository): Response
    {
        return $this->render('commantaire_publication/index.html.twig', [
            'commantaire_publications' => $commantairePublicationRepository->findAll(),
        ]);
    }
    #[Route('/{id}', name: 'admin_commantaire_publication_delete', methods: ['POST'])]
    public function delete(Request $request, CommantairePublication $commantairePublication, CommantairePublicationRepository $commantairePublicationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commantairePublication->getId(), $request->request->get('_token'))) {
            $commantairePublicationRepository->remove($commantairePublication, true);
        }

        return $this->redirectToRoute('adminshowcommentsforonepub', ['id' => $commantairePublication->getPublication()->getId()], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}', name: 'adminshowcommentsforonepub', methods: ['GET'])]
    public function showcommm(CommantairePublicationRepository $commantairePublicationRepository,$id): Response
    {
        $pub=$this->getDoctrine()->getRepository(CommantairePublication::class)->findBy(['Publication'=>$id]);
        return $this->render('commantaire_publication/admin_index.html.twig', [
            'commantaire_publications' => $commantairePublicationRepository->findBy(['id'=>$pub]),
        ]);
    }


    #[Route('/{id}', name: 'admin_commantaire_publication_show', methods: ['GET'])]
    public function show(CommantairePublication $commantairePublication): Response
    {

        return $this->render('commantaire_publication/show.html.twig', [
            'commantaire_publication' => $commantairePublication,
        ]);
    }

    #[Route('/{id}', name: 'admin_commantaire_publication', methods: ['GET'])]
    public function showComments(CommantairePublication $commantairePublication): Response
    {
        return $this->render('commantaire_publication/show.html.twig', [
            'commantaire_publication' => $commantairePublication,
        ]);
    }

    


}