<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Mailer\MyEmail;

#[Route('/evenement')]
class EvenementController extends AbstractController
{
    #[Route('/admin', name: 'app_evenement_index', methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository): Response
    {
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EvenementRepository $evenementRepository): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('Imageevenement')->getData();
            if ($pictureFile) {
                $pictureFileName = uniqid() . '.' . $pictureFile->guessExtension();
                $pictureFile->move(
                    $this->getParameter('pictures_directory_events'),
                    $pictureFileName
                );
                $pictureFileName = 'Back/images/events/' . $pictureFileName;
                $evenement->setImageevenement($pictureFileName);
            }
            else
                $evenement->setImageevenement("Back/images/events/NoImageFound.png");
            $evenementRepository->save($evenement, true);

            return $this->redirectToRoute('app_adresse_newevent', [
                'id'=>$evenement->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement,EvenementRepository $evenementRepository): Response
    {
        $eventLikes = count($evenement->getEventLikes());
        $evenement->setNumberoflikes($eventLikes);
        $nombreParticipantRestant =$evenement->getNombreParticipantEvenement()- count($evenement->getUtilisateurs());
        $evenement->setPlacesRestantes($nombreParticipantRestant);
        $evenementRepository->save($evenement, true);
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
            'eventLikes' => $eventLikes,
            'placesrestante' => $nombreParticipantRestant,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('Imageevenement')->getData();
            if ($pictureFile) {
                $pictureFileName = uniqid() . '.' . $pictureFile->guessExtension();
                $pictureFile->move(
                    $this->getParameter('pictures_directory_events'),
                    $pictureFileName
                );
                $pictureFileName = 'Back/images/events/' . $pictureFileName;
                $evenement->setImageevenement($pictureFileName);
            }
            $evenementRepository->save($evenement, true);

            return $this->redirectToRoute('app_adresse_edit_addr_event', [
                'id'=>$evenement->getAdresse()->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $evenementRepository->remove($evenement, true);
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/', name: 'app_evenement_user_index', methods: ['GET'])]
    public function user_index(EvenementRepository $evenementRepository): Response
    {
        return $this->render('evenement/user_events.html.twig', [
            'evenements' => $evenementRepository->findAll(),
        ]);
    }

    #[Route('/{id}/like', name: 'eventlikes', methods: ['POST', 'GET'])]
    public function like(Evenement $evenement, UtilisateurRepository $utilisateurRepository): Response
    {
        $user = $utilisateurRepository->find(13); // replace 1 with the actual user ID
        $eventLikes = $evenement->getEventLikes();

        if (!$eventLikes->contains($user)) {
            $evenement->addEventLike($user);
        } else {
            $evenement->removeEventLike($user);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->redirectToRoute('app_evenement_show', ['id' => $evenement->getId()]);
    }


    #[Route('/{id}/participe', name: 'evenement_participe', methods: ['POST','GET'])]
    public function participe(Evenement $evenement,UtilisateurRepository $utilisateurRepository,MyEmail $myEmail): Response
    {
        $user = $utilisateurRepository->find(1);
        $participant = $evenement->getUtilisateurs();
        $nombreParticipantRestant =$evenement->getNombreParticipantEvenement()- count($evenement->getUtilisateurs());
        if (!$participant->contains($user)) {
            if ($nombreParticipantRestant > 0){
                $evenement->addUtilisateur($user);
                $this->addFlash('success', 'Merci pour la participation, vous recevrez un email pour plus de détails');
                $myEmail->send($utilisateurRepository,$evenement);
            }
            else {

                $this->addFlash('danger', 'désolé, il n\'y a plus de places');

            }

        } else {
            $evenement->removeUtilisateur($user);
            $this->addFlash('danger', 'vous avez annulé votre participation, vous pouvez choisir un autre événement');
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->redirectToRoute('app_evenement_show', ['id' => $evenement->getId()]);

    }
}
