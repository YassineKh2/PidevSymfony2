<?php

namespace App\Controller;

use App\Entity\Despense;
use App\Entity\Formation;
use App\Entity\Session;
use App\Form\RechercheFormType;
use App\Form\SearchFormType;
use App\Form\SessionType;
use App\Repository\FormationRepository;
use App\Repository\SessionRepository;

use App\Repository\UserRepository;
use App\Repository\UtilisateurRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/session')]
class SessionController extends AbstractController
{
    #[Route('/', name: 'app_session_index', methods: ['GET','POST'])]
    public function index(SessionRepository $sessionRepository,Request $request,PaginatorInterface $paginator): Response
    {
        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->getData()['search'] != '') {
            $searchTerm = $form->getData()['search'];

            $sessions=$paginator->paginate($sessionRepository->findByNomSession($searchTerm),$request->query->getInt('page',1),3);
            return $this->renderForm('session/index.html.twig', [
                'form' => $form,
                'sessions' => $sessions,
                'firstSession' =>$sessionRepository->FindFirst()[0],
            ]);
        }
        $sessions=$paginator->paginate($sessionRepository->findAll(),$request->query->getInt('page',1),4);
        return $this->renderForm('session/index.html.twig', [
            'sessions' => $sessions,
            'form' => $form,
            'searchResults' => [],
            'firstSession' =>$sessionRepository->FindFirst()[0],

        ]);
    }

    #[Route('/back', name: 'app_session_back_index', methods: ['GET'])]
    public function indexBack(SessionRepository $sessionRepository): Response
    {
        return $this->render('session/index-back.html.twig', [
            'sessions' => $sessionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_session_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SessionRepository $sessionRepository): Response
    {
        $session = new Session();
        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('ImageSession')->getData();
            if ($pictureFile) {
                $pictureFileName = uniqid() . '.' . $pictureFile->guessExtension();
                $pictureFile->move(
                    $this->getParameter('pictures_directory_session'),
                    $pictureFileName
                );
                $pictureFileName = 'Front/session/images/' . $pictureFileName;
                $session->setImageSession($pictureFileName);
            }
            else
                $session->setImageSession("Front/session/images/NoImageFound.png");
            $sessionRepository->save($session, true);

            return $this->redirectToRoute('app_session_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('session/new.html.twig', [
            'session' => $session,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_session_show', methods: ['GET'])]
    public function show(Session $session): Response
    {
        return $this->render('session/show.html.twig', [
            'session' => $session,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_session_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Session $session, SessionRepository $sessionRepository,MailerInterface $mailer): Response
    {
        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('ImageSession')->getData();
            if ($pictureFile) {
                $pictureFileName = uniqid() . '.' . $pictureFile->guessExtension();
                $pictureFile->move(
                    $this->getParameter('pictures_directory_session'),
                    $pictureFileName
                );
                $pictureFileName = 'Front/session/images/' . $pictureFileName;
                $session->setImageSession($pictureFileName);
            }


            $sessionRepository->save($session, true);

            return $this->redirectToRoute('app_session_back_index', [], Response::HTTP_SEE_OTHER);

        }

        return $this->renderForm('session/edit.html.twig', [
            'session' => $session,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_session_delete', methods: ['POST','GET'])]
    public function delete(Request $request, Session $session, SessionRepository $sessionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$session->getId(), $request->request->get('_token'))) {
            $sessionRepository->remove($session, true);

        }

        return $this->redirectToRoute('app_session_back_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/joindre/{id}', name: 'app_session_joindre', methods: ['GET','POST'])]
    public function joindreSession(Formation $session,Request $request,UserRepository $userRepository,$id,FormationRepository $sessionRepository): Response
    {
        $token = $this->get('security.token_storage')->getToken();
        if ($token) {
            $user = $token->getUser();
            if ($user) {
                $userId = $user->getId();
            }
        }
        $user=$userRepository->find($userId);
        $session=$sessionRepository->find($id);
        $session->addParticipantsUser($user);

        $sessionRepository->save($session,true);
        if($user->getSession()){
            $this->addFlash('error','tu as deja joindre cette session');
            return $this->redirectToRoute('app_session_show',['id'=>$session->getId()],Response::HTTP_SEE_OTHER);
        }
        else{
            $this->addFlash('error','tu as joindre cette session');
            return $this->redirectToRoute('app_session_show',['id'=>$session->getId()],Response::HTTP_SEE_OTHER);
        }





    }


}
