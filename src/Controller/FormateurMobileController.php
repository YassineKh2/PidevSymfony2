<?php

namespace App\Controller;


use App\Entity\Formateur;
use App\Repository\FormateurRepository;


use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class FormateurMobileController extends AbstractController
{

    #[Route('/AllFormateurMobile', name: '$app_formateur_showJson', methods: ['GET', 'POST'])]
    public function ShowFormateurMobile(FormateurRepository $formateurRepository,NormalizerInterface $normalizer)
    {

        $formateurs = $formateurRepository->findAll();


        $formateurArray = array();

        foreach ($formateurs as $formateur) {
            $formateursData = [
                    'id' => $formateur->getId(),
                    'NomFormateur' => $formateur->getNomFormateur(),
                    'PrenomFormateur' => $formateur->getPrenomFormateur(),
                    'SexeFormateur' => $formateur->getSexeFormateur(),
                    'EmailFormateur' => $formateur->getEmailFormateur(),
                    'NumTelFormateur' => $formateur->getNumTelFormateur(),
                    'ImageFormateur' => $formateur->getImageFormateur(),


                ];

            array_push($formateurArray, $formateursData);
        }





        return new JsonResponse($formateurArray);

    }

    #[Route('/FindFormateurMobile/{id}', name: '$app_formateur_findJson', methods: ['GET', 'POST'])]
    public function FindFormateurMobile(FormateurRepository $formateurRepository,NormalizerInterface $normalizer,$id)
    {

        $formateur = $formateurRepository->find($id);
        $formateurData= [
            'id' => $formateur->getId(),
            'NomFormateur' => $formateur->getNomFormateur(),
            'PrenomFormateur' => $formateur->getPrenomFormateur(),
            'SexeFormateur' => $formateur->getSexeFormateur(),
            'EmailFormateur' => $formateur->getEmailFormateur(),
            'NumTelFormateur' => $formateur->getNumTelFormateur(),
            'ImageFormateur' => $formateur->getImageFormateur(),


        ];





        return new JsonResponse($formateurData);
    }

    #[Route('/AddFormateurMobile', name: '$app_formateur_AddJson', methods: ['GET', 'POST'])]
    public function AddFormateurMobile(FormateurRepository $formateurRepository,NormalizerInterface $normalizer,Request $request)
    {

        $formateur = new Formateur();

        $formateur->setNomFormateur($request->get('NomFormateur'));
        $formateur->setImageFormateur($request->get('ImageFormateur'));
        $formateur->setPrenomFormateur($request->get('PrenomFormateur'));

        $formateur->setSexeFormateur($request->get('SexeFormateurFormateur'));
        $formateur->setNumTelFormateur($request->get('NumTelFormateur'));
        $formateur->setEmailFormateur($request->get('EmailFormateur'));



        $formateurRepository->save($formateur,true);

        $formateurs = $formateurRepository->findAll();
        $jsoncontent = $normalizer->normalize($formateurs,'json',['groups' => "Formateur"]);


        return new JsonResponse($jsoncontent);
    }

    #[Route('/ModifyFormateurMobile/{id}', name: '$app_formateur_ModifyJson', methods: ['GET', 'POST'])]
    public function ModifyFormateurMobile(FormateurRepository $formateurRepository,NormalizerInterface $normalizer,Request $request,$id)
    {



        $formateur = $formateurRepository->find($id);
        $formateur->setNomFormateur($request->get('NomFormateur'));
        $formateur->setImageFormateur($request->get('ImageFormateur'));
        $formateur->setPrenomFormateur($request->get('PrenomFormateur'));

        $formateur->setSexeFormateur($request->get('SexeFormateurFormateur'));
        $formateur->setNumTelFormateur($request->get('NumTelFormateur'));
        $formateur->setEmailFormateur($request->get('EmailFormateur'));


        $formateurRepository->save($formateur,true);
        $formateur = $formateurRepository->find($id);

        $jsoncontent = $normalizer->normalize($formateur,'json',['groups' => "Formateur"]);


        return new JsonResponse($jsoncontent);
    }

    #[Route('/DeleteFormateurMobile', name: '$app_formateur_DeleteJson', methods: ['GET', 'POST'])]
    public function deleteFormateurMobile(Request $request,ManagerRegistry $doctrine): JsonResponse
    {
        $id = $request->get("id");

        $em = $doctrine->getManager();
        $formateur = $em->getRepository(Formateur::class)->find($id);
        if($formateur!=null ) {
            $em->remove($formateur);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("Formateur a ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id formateur invalide.");


    }
}