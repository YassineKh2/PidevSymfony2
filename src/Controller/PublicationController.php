<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Entity\ReactionPublication;
use App\Entity\Utilisateur;
use App\Entity\CommantairePublication;
use App\Form\PublicationType;
use App\Repository\PublicationRepository;
use App\Repository\ReactionPublicationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


#[Route('/forum')]
class PublicationController extends AbstractController
{
    #[Route('/', name: 'app_publication_index', methods: ['GET'])]
    public function index(PublicationRepository $publicationRepository,  Request $request,PaginatorInterface $paginator ): Response
    {
        $publications = $publicationRepository->findAll();
        $publications = $paginator->paginate(
            $publications, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );
        return $this->render('publication/index.html.twig', [
            'publications' => $publications,
            
            
        ]);
    }

    #[Route('/new', name: 'app_publication_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PublicationRepository $publicationRepository,UserRepository $userR): Response
    {
        
        $publication = new Publication();
        $token = $this->get('security.token_storage')->getToken();
        if ($token) {
            $user = $token->getUser();
            if ($user) {
                $userId = $user->getId();
            }
        }
        $user = $userR->find($userId);
        $publication->setUser($user);

        $publication->setDatePublication(new \DateTime("now"));
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('ImageForum')->getData();
            if ($pictureFile) {
                $pictureFileName = uniqid() . '.' . $pictureFile->guessExtension();
                $pictureFile->move(
                    $this->getParameter('pictures_directory_forum'),
                    $pictureFileName
                );
                $pictureFileName = 'Back/images/forum/' . $pictureFileName;
                $publication->setImageForum($pictureFileName);
            }
            
            $publicationRepository->save($publication, true);

            return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('publication/new.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_publication_show', methods: ['GET'])]
    public function show(Publication $publication): Response
    {
        return $this->render('publication/show.html.twig', [
            'publication' => $publication,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_publication_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Publication $publication, PublicationRepository $publicationRepository): Response
    {
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('ImageForum')->getData();
            if ($pictureFile) {
                $pictureFileName = uniqid() . '.' . $pictureFile->guessExtension();
                $pictureFile->move(
                    $this->getParameter('pictures_directory_forum'),
                    $pictureFileName
                );
                $pictureFileName = 'Back/images/forum/' . $pictureFileName;
                $publication->setImageForum($pictureFileName);
            }

            $publicationRepository->save($publication, true);

            return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('publication/edit.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_publication_delete', methods: ['POST'])]
    public function delete(Request $request, Publication $publication, PublicationRepository $publicationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publication->getId(), $request->request->get('_token'))) {
            $publicationRepository->remove($publication, true);
        }

        return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
    }
    

    #[Route('/{id}/reaction', name: 'app_publication_reaction', methods: ['POST', 'GET'])]
    public function like(Publication $pub, EntityManagerInterface $entityManager,UserRepository $userRepository, ReactionPublicationRepository $reactPubRepo, $id,PublicationRepository $publicationRepository ): Response
    {

        $token = $this->get('security.token_storage')->getToken();
        if ($token) {
            $user = $token->getUser();
            if ($user) {
                $userId = $user->getId();
            }
        }
        $user = $userRepository->find($userId);

        $pub=$publicationRepository->find($id);

        if (!$user)  return $this->json(['code' => 403, 'message' => 'Unauthorized'], 403);
       // if (!$user)  return new JsonResponse(['code' => 403, 'message' => 'Unauthorized'], 403);

        if($pub->isLikedByUser($user)){
            $like=$reactPubRepo->findOneBy([
                'Publication'=>$pub,
                'User'=>$user
            ]);
            $entityManager->remove($like);
            $entityManager->flush();
            return $this->json(['code' => 200, 'message' => 'like deleted','likes' => $reactPubRepo->count(['Publication'=>$pub])],200);
        }
        $like= new ReactionPublication();

        $like->setPublication($pub)
            ->setUser($user);

        $entityManager->persist($like);
        $entityManager->flush();

        return $this->json(['code' => 200, 'message' => 'like added','likes'=> $reactPubRepo->count(['Publication'=>$pub])],200);

    }
}