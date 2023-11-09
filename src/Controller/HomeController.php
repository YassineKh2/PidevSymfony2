<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->redirectToRoute('home');
    }

    #[Route('/home', name: 'home')]
    public function home(): Response
    {
        if($this->getUser()->isStatus()!= null)
        {
            return $this->render('home/ban.html.twig', [
                'controller_name' => 'HomeController',  ]);
        }
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/therapist', name: 'therapist')]
    public function therapist(): Response
    {
        if($this->getUser()->isStatus()!= null)
        {
            return $this->render('home/ban.html.twig', [
            'controller_name' => 'HomeController',  ]);
        }
        return $this->render('home/therapist.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/patient', name: 'patient')]
    public function patient(): Response
    {
        return $this->render('home/patient.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/homeAdmin', name: 'homeAdmin')]
    public function homeAdmin(): Response
    {
        return $this->render('home/homeAdmin.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/center', name: 'center')]
    public function centre(): Response
    {
        if($this->getUser()->isApprove()== null)
        {
            return $this->render('home/centerVerification.html.twig', [
                'controller_name' => 'HomeController',  ]);
        }
        return $this->render('home/center.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/listTherapist', name: 'listTherapist')]
    public function listTherapist(ManagerRegistry $doctrine): Response
    {
        $user= $doctrine->getRepository(User::class)->findAll();
        return $this->render('home/listTherapist.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/listCenter', name: 'listCenter')]
    public function listCenter(ManagerRegistry $doctrine): Response
    {
        $user= $doctrine->getRepository(User::class)->findAll();
        return $this->render('home/listCenter.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/listPatient', name: 'listPatient')]
    public function listPatient(ManagerRegistry $doctrine): Response
    {
        $user= $doctrine->getRepository(User::class)->findAll();
        return $this->render('home/listPatient.html.twig', [
            'user' => $user,
        ]);
    }
}
