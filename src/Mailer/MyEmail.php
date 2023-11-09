<?php

namespace App\Mailer;

use App\Entity\Evenement;
use App\Repository\EvenementRepository;
use App\Repository\UtilisateurRepository;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MyEmail
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send( UtilisateurRepository $utilisateurRepository ,Evenement $evenement)
    {
        $user = $utilisateurRepository->find(1);
        $evenementparticipe =$evenement->getNomEvenement()."\n Description: ".$evenement->getDescription();
        $email = (new Email())
            ->from('rehabradar@gmail.com')
            ->to($user->getEmailUtilisateur())
            ->subject('participation ')
            ->text('Merci pour votre participation a '.$evenementparticipe);

        $this->mailer->send($email);
    }
}
