<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminApproveType;
use App\Form\AdminBanType;
use App\Form\ModifierImageType;
use App\Form\ModifierProfileType;
use App\Form\NewPasswordType;
use App\Form\ResetPasswordType;
use App\Form\VerificationCodeFormType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\String\Slugger\SluggerInterface;
use Twilio\Rest\Client;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    #[Route('modifierProfile/{id}', name: 'modifierProfile')]
    public function modifierProfile(ManagerRegistry $doctrine,$id,Request $req): Response {


        $em = $doctrine->getManager();
        $user = $doctrine->getRepository(User::class)->find($id);
        $form = $this->createForm(ModifierProfileType::class,$user);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em->persist($user);
            $em->flush();
            $roles=$user->getRoles();
            if(in_array('ROLE_THERAPIST', $roles)) {

                return $this->redirectToRoute('therapist');
            }
            if(in_array('ROLE_PATIENT', $roles)) {

                return $this->redirectToRoute('patient');
            }
        }

        return $this->renderForm('home/modifierProfile.html.twig',['form'=>$form]);

    }
    #[Route('deleteAccount/{id}', name: 'deleteAccount')]
    public function deleteAccount(ManagerRegistry $doctrine,$id): Response
    {

        $em= $doctrine->getManager();
        $user= $doctrine->getRepository(User::class)->find($id);
        $em->remove($user);
        $em->flush();
        if($this->getUser()->getNom()== 'admin'){return $this->redirectToRoute('homeAdmin');}
        else
        return $this->redirectToRoute('home');
    }
    #[Route('modifierImage/{id}', name: 'modifierImage')]
    public function modifierImage(ManagerRegistry $doctrine,$id,Request $req,SluggerInterface $slugger,User $user,): Response
    {
        $form = $this->createForm(ModifierImageType::class, $user);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $eventImage */
            $eventImage = $form->get('image')->getData();

            // this condition is needed because the 'eventImage' field is not required
            // so the Image file must be processed only when a file is uploaded
            if ($eventImage) {
                $originalFilename = pathinfo($eventImage->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $eventImage->guessExtension();

                // Move the file to the directory where images are stored
                try {
                    $eventImage->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'eventImage' property to store the image file name
                // instead of its contents
                $user->setImage($newFilename);
            }
            $this->getDoctrine()->getManager()->flush();
            $roles = $user->getRoles();
            if (in_array('ROLE_THERAPIST', $roles)) {

                return $this->redirectToRoute('therapist', [], Response::HTTP_SEE_OTHER);
            }
            if (in_array('ROLE_PATIENT', $roles)) {

                return $this->redirectToRoute('therapist', [], Response::HTTP_SEE_OTHER);
            }
        }
        return $this->renderForm('home/modifierImage.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('adminBan/{id}', name: 'adminBan')]
    public function adminBan(ManagerRegistry $doctrine,$id,Request $req): Response
    {
        $em = $doctrine->getManager();
        $user = $doctrine->getRepository(User::class)->find($id);
        $users = $doctrine->getRepository(User::class)->find($id);
        $form = $this->createForm(AdminBanType::class,$user);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em->persist($user);
            $em->flush();
            $roles=$user->getRoles();

            return $this->redirectToRoute('listTherapist');

        }
        return $this->renderForm('home/adminBan.html.twig',[
            'users' => $users,
            'form'=>$form
        ]);

    }
    #[Route('adminApprove/{id}', name: 'adminApprove')]
    public function adminApprove(ManagerRegistry $doctrine,$id,Request $req): Response
    {
        $em = $doctrine->getManager();
        $user = $doctrine->getRepository(User::class)->find($id);
        $users = $doctrine->getRepository(User::class)->find($id);
        $form = $this->createForm(AdminApproveType::class,$user);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em->persist($user);
            $em->flush();
            $roles=$user->getRoles();

            return $this->redirectToRoute('listTherapist');

        }
        return $this->renderForm('home/adminApprove.html.twig',[
            'users' => $users,
            'form'=>$form
        ]);

    }  #[Route('centerApprove/{id}', name: 'centerApprove')]
    public function centerApprove(ManagerRegistry $doctrine,$id,Request $req): Response
    {
        $em = $doctrine->getManager();
        $user = $doctrine->getRepository(User::class)->find($id);
        $users = $doctrine->getRepository(User::class)->find($id);
        $form = $this->createForm(AdminApproveType::class,$user);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em->persist($user);
            $em->flush();
            $roles=$user->getRoles();

            return $this->redirectToRoute('listCenter');

        }
        return $this->renderForm('home/adminApprove.html.twig',[
            'users' => $users,
            'form'=>$form
        ]);

    }

    #[Route(path: '/resetPassword', name: 'resetPassword')]
    public function resetPassword(Request $request, UserRepository $user, TokenGeneratorInterface $tokenGenerator): Response
    {
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $donnees = $form->getData();


            $recevier= $user->findOneByNumero($donnees->getNumero());
            $idsms = $recevier->getId();
            $tokensalt = bin2hex(random_bytes(2));

            try {
                $recevier->setResetToken($tokensalt);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($recevier);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());

                return $this->redirectToRoute('resetPassword');
            }
            $sid = 'AC2277d555302c574d80cc9d2e51bc43fa';
            $token = 'a7b3e8d2bfb37eae575461edee8ad1f3';
            $client = new Client($sid, $token);

            $client->messages->create(
            // the number you'd like to send the message to
                $recevier->getNumero(),
                [
                    // A Twilio phone number you purchased at twilio.com/console
                    'from' => '+1 567 485 3710',
                    // the body of the text message you'd like to send
                    'body' => 'Your verification code is :' .$tokensalt
                ]
            );

            return $this->redirectToRoute('code_app',['idsms' => $idsms]);
        }
        return $this->render('home/resetPassword.html.twig',
            ['form' => $form->createView()]);
    }

    #[Route(path: '/verificationCode/{idsms}', name: 'code_app')]
    public function VerificationCode(Request $request, UserRepository $user,ManagerRegistry $doctrine,$idsms): Response
    {

        $form = $this->createForm(VerificationCodeFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $donnees = $form->getData();
            $recevier= $user->findOneBySalt($donnees->getResetToken());

            try {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($recevier);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());

                return $this->redirectToRoute('resetPassword');
            }

            return $this->redirectToRoute('newPassword',['idsms' => $idsms]);
        }
        return $this->render('home/verificationCode.html.twig',
            ['form' => $form->createView()]);
    }
    #[Route('newPassword/{idsms}', name: 'newPassword')]
    public function newPassword(ManagerRegistry $doctrine,$idsms,Request $req , UserPasswordHasherInterface $userPasswordHasher): Response {

        $em = $doctrine->getManager();
        $user = $doctrine->getRepository(User::class)->find($idsms);
        $form = $this->createForm(NewPasswordType::class,$user);
        $form->handleRequest($req);
        if($form->isSubmitted())
        {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_login');

        }

        return $this->renderForm('home/newPassword.html.twig',['form'=>$form]);

    }

}
