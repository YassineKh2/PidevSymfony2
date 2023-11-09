<?php

namespace App\Controller;


use App\Entity\Formation;
use App\Repository\FormateurRepository;
use App\Repository\FormationRepository;

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


class FormationMobileController extends AbstractController
{

    #[Route('/AllFormationMobile', name: '$app_formation_showJson', methods: ['GET', 'POST'])]
    public function ShowFormationMobile(FormationRepository $formationRepository,NormalizerInterface $normalizer,FormateurRepository $formateurRepository)
    {

        $formations = $formationRepository->findAll();


        $formationArray = array();


        foreach ($formations as $formation) {
            $moduleArray = array();
            foreach($formation->getmoduleFormations() as $module){
                $moduleData=[
                    'id' =>$module->getId(),
                    'NomModule' => $module->getNomModule(),
                    'PrerequisModule' => $module->getPrerequisModule(),
                    'DureeModule' => $module->getDureeModule(),
                    'ContenuModule' => $module->getContenuModule(),


                    ];
                array_push($moduleArray, $moduleData);
            }
            $formateurArray = array(['id' => $formation->getFormateur()->getId(),
                'NomFormateur' => $formation->getFormateur()->getNomFormateur(),
                'PrenomFormateur' => $formation->getFormateur()->getPrenomFormateur(),
                'SexeFormateur' => $formation->getFormateur()->getSexeFormateur(),
                'EmailFormateur' => $formation->getFormateur()->getEmailFormateur(),
                'NumTelFormateur' => $formation->getFormateur()->getNumTelFormateur(),
                'ImageFormateur' => $formation->getFormateur()->getImageFormateur(),
            ]);
            $formationsData = [
                'id' => $formation->getid(),
                'modules' =>$moduleArray,
                'Formateur' => $formateurArray,


                'NomFormation' => $formation->getNomFormation(),
                'NiveauFormation' => $formation->getNiveauFormation(),
                'ImageFormation' => $formation->getImageFormation(),
                'DescriptionFormation' => $formation->getDescriptionFormation(),

            ];

            array_push($formationArray, $formationsData);
        }





        return new JsonResponse($formationArray);

    }

    #[Route('/FindFormationMobile/{id}', name: '$app_formation_findJson', methods: ['GET', 'POST'])]
    public function FindFormationMobile(FormationRepository $formationRepository,NormalizerInterface $normalizer,$id)
    {

        $formations = $formationRepository->find($id);

        $formationNormalizees = $normalizer->normalize($formations,'json',['groups' => "Formation"]);



        return new JsonResponse($formationNormalizees);
    }

    #[Route('/AddFormationMobile', name: '$app_formation_AddJson', methods: ['GET', 'POST'])]
    public function AddFormationMobile(FormationRepository $formationRepository,NormalizerInterface $normalizer,Request $request,FormateurRepository $formateurRepository)
    {

        $formation = new Formation();

        $formation->setNomFormation($request->get('NomFormation'));

        $formation->setNiveauFormation($request->get('NiveauFormation'));
        $formation->setImageFormation($request->get('ImageFormation'));

        $formation->setDescriptionFormation($request->get('DescriptionFormation'));
        $formateur=$formateurRepository->find($request->get('idFormateur'));
        $formation->setFormateur($formateur);



        $formationRepository->save($formation,true);

        $formations = $formationRepository->findAll();
        $jsoncontent = $normalizer->normalize($formations,'json',['groups' => "Formation"]);


        return new JsonResponse($jsoncontent);
    }

    #[Route('/ModifyFormationMobile/{id}', name: '$app_formation_ModifyJson', methods: ['GET', 'POST'])]
    public function ModifyFormationMobile(FormationRepository $formationRepository,NormalizerInterface $normalizer,Request $request,$id,FormateurRepository $formateurRepository)
    {



        $formation = $formationRepository->find($id);
        $formation->setNomFormation($request->get('NomFormation'));
        $formation->setNiveauFormation($request->get('NiveauFormation'));
        $formation->setImageFormation($request->get('ImageFormation'));
        $formation->setDescriptionFormation($request->get('DescriptionFormation'));
        $formateur=$formateurRepository->find($request->get('idFormateur'));
        $formation->setFormateur($formateur);

        $formationRepository->save($formation,true);
        $formation = $formationRepository->find($id);

        $jsoncontent = $normalizer->normalize($formation,'json',['groups' => "Formation"]);


        return new JsonResponse($jsoncontent);
    }

    #[Route('/DeleteFormationMobile', name: '$app_formation_DeleteJson', methods: ['GET', 'POST'])]
    public function deleteFormationMobile(Request $request,ManagerRegistry $doctrine): JsonResponse
    {
        $id = $request->get("id");

        $em = $doctrine->getManager();
        $formation = $em->getRepository(Formation::class)->find($id);
        if($formation!=null ) {
            $em->remove($formation);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("Formation a ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id formation invalide.");


    }
}