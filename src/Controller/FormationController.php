<?php

namespace App\Controller;

use App\Entity\Formateur;
use App\Entity\Formation;
use App\Entity\Utilisateur;
use App\Form\ContactFormateurType;
use App\Form\FormationType;
use App\Form\RechercheFormType;
use App\Form\SearchFormType;
use App\Repository\FormationRepository;
use App\Repository\FormateurRepository;
use App\Repository\UserRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Knp\Component\Pager\{Pagination\PaginationInterface, PaginatorInterface};
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/formation')]
class FormationController extends AbstractController
{
    #[Route('/', name: 'app_formation_index', methods: ['GET', 'POST'])]
    public function index(FormationRepository $formationRepository, Request $request,PaginatorInterface $paginator): Response
    {
        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->getData()['search'] != '') {
            $searchTerm = $form->getData()['search'];

            $formations=$paginator->paginate($formationRepository->findByNomFormation($searchTerm),$request->query->getInt('page',1),3);
            return $this->renderForm('formation/index-front.html.twig', [
                'formations' => $formations,
                'form' => $form
            ]);
        }
        $formations=$paginator->paginate($formationRepository->findAll(),$request->query->getInt('page',1),4);
        return $this->renderForm('formation/index-front.html.twig', [
            'formations' => $formations,
            'form' => $form,
            'searchResults' => [],

        ]);
    }

    #[Route('/back', name: 'app_formation_index_back', methods: ['GET'])]
    public function indexback(FormationRepository $formationRepository): Response
    {

        return $this->render('formation/index-back.html.twig', [
            'formations' => $formationRepository->findAll(),



        ]);
    }

    #[Route('/new', name: 'app_formation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FormationRepository $formationRepository,FormateurRepository $formateurRepository): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('ImageFormation')->getData();
            if ($pictureFile) {
                $pictureFileName = uniqid() . '.' . $pictureFile->guessExtension();
                $pictureFile->move(
                    $this->getParameter('pictures_directory_formation'),
                    $pictureFileName
                );
                $pictureFileName = 'Front/formation/images/' . $pictureFileName;
                $formation->setImageFormation($pictureFileName);
            }
            else
                $formation->setImageFormation("Front/formation/images/NoImageFound.png");
            $formationRepository->save($formation, true);


            return $this->redirectToRoute('app_formation_index_back', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('formation/new.html.twig', [
            'formation' => $formation,
            'form' => $form,

        ]);
    }



    #[Route('/{id}/edit', name: 'app_formation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Formation $formation, FormationRepository $formationRepository): Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('ImageFormation')->getData();
            if ($pictureFile) {
                $pictureFileName = uniqid() . '.' . $pictureFile->guessExtension();
                $pictureFile->move(
                    $this->getParameter('pictures_directory_formation'),
                    $pictureFileName
                );
                $pictureFileName = 'Front/formation/images/' . $pictureFileName;
                $formation->setImageFormation($pictureFileName);
            }
            $formationRepository->save($formation, true);

            return $this->redirectToRoute('app_formation_index_back', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('formation/edit.html.twig', [
            'formation' => $formation,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_formation_delete', methods: ['POST'])]
    public function delete(Request $request, Formation $formation, FormationRepository $formationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $formation->getId(), $request->request->get('_token'))) {
            $formationRepository->remove($formation, true);
        }

        return $this->redirectToRoute('app_formation_index_back', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_formation_show', methods: ['GET','POST'])]
    public function show(Formation $formation,Request $request,MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactFormateurType::class);
        $contact=$form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $email=(new TemplatedEmail())
                ->from($contact->get('email')->getData())
                ->to($formation->getFormateur()->getEmailFormateur())
                ->subject('Contacter au Priére de me répondre"'.$formation->getNomFormation().'"')
                ->htmlTemplate('formation/contact_formateur.html.twig')
                ->context([
                    'formation'=>$formation,
                    'mail'=>$contact->get('email')->getData(),
                    'emailFr'=>$formation->getFormateur()->getEmailFormateur(),
                    'message'=>$contact->get('message')->getData(),
                ]);
            $mailer->send($email);
            $this->addFlash('message','Votre email a été bien Envoyer');
            return $this->redirectToRoute('app_formation_show',['id'=>$formation->getId()],Response::HTTP_SEE_OTHER);
        }

        return $this->render('formation/show.html.twig', [
            'formation' => $formation,
            'formContact' => $form->createView()


        ]);
    }
    #[Route('/joindre/{id}', name: 'app_formation_joindre', methods: ['GET','POST'])]
    public function joindre(Formation $formation,Request $request,UserRepository $userRepository,$id,FormationRepository $formationRepository): Response
    {   $token = $this->get('security.token_storage')->getToken();
        if ($token) {
            $user = $token->getUser();
            if ($user) {
                $userId = $user->getId();
            }
        }
        $user=$userRepository->find($userId);
        $formation=$formationRepository->find($id);

        if($user->getFormation()){
            $this->addFlash('message','tu as deja joindre cette formation');
            return $this->redirectToRoute('app_formation_show',['id'=>$formation->getId()],Response::HTTP_SEE_OTHER);
        }
        else{
            $formation->addParticipantsUser($user);
            $formationRepository->save($formation,true);
            $this->addFlash('message','tu as joindre cette formation');
            return $this->redirectToRoute('app_formation_show',['id'=>$formation->getId()],Response::HTTP_SEE_OTHER);
        }





    }
}
