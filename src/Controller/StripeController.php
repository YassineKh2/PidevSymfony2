<?php

namespace App\Controller;

use App\Entity\Session;
use App\Repository\SessionRepository;
use App\Repository\UserRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe;
class StripeController extends AbstractController
{
    #[Route('/stripe/{id}', name: 'app_stripe')]
    public function indexStripe($id,SessionRepository $sessionRepository,UserRepository $userRepository): Response
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
        if ($user->getSession()) {
            $this->addFlash('error', 'tu as deja joindre cette session');
            return $this->redirectToRoute('app_session_show', ['id' => $session->getId()], Response::HTTP_SEE_OTHER);
        }
        elseif(count($session->getParticipantSession()) > $session->getNombreParticipantSession()){
            $this->addFlash('error','cette session est deja complet');
        }
        else{
        return $this->render('stripe/index.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
            'session'=>$session

        ]);}
    }


    #[Route('/stripe/create-charge/{id}', name: 'app_stripe_charge', methods: ['POST'])]
    public function createCharge(Request $request,SessionRepository $sessionRepository,$id,UserRepository $userRepository)
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
        $session->addParticipantSession($user);

        $sessionRepository->save($session,true);
        $montant = $session->getDespense()->getMontantDespense();
        if ($user->getSession()) {
            $this->addFlash('message', 'tu as deja joindre cette session');
            return $this->redirectToRoute('app_session_show', ['id' => $session->getId()], Response::HTTP_SEE_OTHER);
        }
        elseif(count($session->getParticipantSession()) > $session->getNombreParticipantSession()){
            $this->addFlash('message','cette session est deja complet');
        }
        else {
            Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
            Stripe\Charge::create([
                "amount" => $montant * 100,
                "currency" => "usd",
                "source" => $request->request->get('stripeToken'),
                "description" => "Binaryboxtuts Payment Test"
            ]);
            $this->addFlash(
                'success',
                'Succés Payment,Tu as joindre cette session. on va vous contacter avec le lien de meet sur votre mail ' . $montant
            );
            return $this->redirectToRoute('app_stripe', ['id' => $id], Response::HTTP_SEE_OTHER);
        }
    }
}
