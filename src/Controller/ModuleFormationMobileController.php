<?php

namespace App\Controller;

use App\Entity\ModuleFormation;
use App\Repository\FormateurRepository;
use App\Repository\FormationRepository;
use App\Repository\ModuleFormationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ModuleFormationMobileController extends AbstractController
{
    #[Route('/AllModuleFormationMobile', name: '$app_module_showJson', methods: ['GET', 'POST'])]
    public function ShowModuleFormationMobile(ModuleFormationRepository $moduleRepository,NormalizerInterface $normalizer)
    {

        $modules = $moduleRepository->findAll();


        $moduleArray = array();

        foreach ($modules as $module) {

                $moduleData=[
                    'id' =>$module->getId(),
                    'NomModule' => $module->getNomModule(),
                    'PrerequisModule' => $module->getPrerequisModule(),
                    'DureeModule' => $module->getDureeModule(),
                    'ContenuModule' => $module->getContenuModule(),
                    'Formation'=>[
                        'NomModuleFormation' => $module->getFormation()->getNomFormation(),
                        'NiveauModuleFormation' => $module->getFormation()->getNiveauFormation(),
                        'ImageModuleFormation' => $module->getFormation()->getImageFormation(),
                        'DescriptionModuleFormation' => $module->getFormation()->getDescriptionFormation(),
                    ]


                ];
                array_push($moduleArray, $moduleData);
            }






        return new JsonResponse($moduleArray);

    }

    #[Route('/FindModuleFormationMobile/{id}', name: '$app_module_findJson', methods: ['GET', 'POST'])]
    public function FindModuleFormationMobile(ModuleFormationRepository $moduleRepository,NormalizerInterface $normalizer,$id)
    {

        $module = $moduleRepository->find($id);
        $moduleData=[
            'id' =>$module->getId(),
            'NomModule' => $module->getNomModule(),
            'PrerequisModule' => $module->getPrerequisModule(),
            'DureeModule' => $module->getDureeModule(),
            'ContenuModule' => $module->getContenuModule(),
            'Formation'=>[
                'NomModuleFormation' => $module->getFormation()->getNomFormation(),
                'NiveauModuleFormation' => $module->getFormation()->getNiveauFormation(),
                'ImageModuleFormation' => $module->getFormation()->getImageFormation(),
                'DescriptionModuleFormation' => $module->getFormation()->getDescriptionFormation(),
            ]


        ];





        return new JsonResponse($moduleData);
    }

    #[Route('/AddModuleFormationMobile', name: '$app_module_AddJson', methods: ['GET', 'POST'])]
    public function AddModuleFormationMobile(ModuleFormationRepository $moduleRepository,NormalizerInterface $normalizer,Request $request,FormationRepository $formationRepository)
    {

        $module = new ModuleFormation();

        $module->setNomModule($request->get('NomModule'));
        $module->setPrerequisModule($request->get('PrerequisModule'));
        $module->setDureeModule($request->get('DureeModule'));

        $module->setContenuModule($request->get('ContenueModule'));
        $formation=$formationRepository->find($request->get('idFormation'));
        $module->setFormation($formation);



        $moduleRepository->save($module,true);

        $modules = $moduleRepository->findAll();
        $jsoncontent = $normalizer->normalize($modules,'json',['groups' => "ModuleFormation"]);


        return new JsonResponse($jsoncontent);
    }

    #[Route('/ModifyModuleFormationMobile/{id}', name: '$app_module_ModifyJson', methods: ['GET', 'POST'])]
    public function ModifyModuleFormationMobile(ModuleFormationRepository $moduleRepository,NormalizerInterface $normalizer,Request $request,$id,FormationRepository $formationRepository)
    {



        $module = $moduleRepository->find($id);
        $module->setNomModule($request->get('NomModule'));
        $module->setPrerequisModule($request->get('PrerequisModule'));
        $module->setDureeModule($request->get('DureeModule'));

        $module->setContenuModule($request->get('ContenueModule'));
        $formation=$formationRepository->find($request->get('idFormation'));
        $module->setFormation($formation);


        $moduleRepository->save($module,true);
        $module = $moduleRepository->find($id);

        $jsoncontent = $normalizer->normalize($module,'json',['groups' => "ModuleFormation"]);


        return new JsonResponse($jsoncontent);
    }

    #[Route('/DeleteModuleFormationMobile', name: '$app_module_DeleteJson', methods: ['GET', 'POST'])]
    public function deleteModuleFormationMobile(Request $request,ManagerRegistry $doctrine): JsonResponse
    {
        $id = $request->get("id");

        $em = $doctrine->getManager();
        $module = $em->getRepository(ModuleFormation::class)->find($id);
        if($module!=null ) {
            $em->remove($module);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("ModuleFormation a ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id module invalide.");


    }
}
