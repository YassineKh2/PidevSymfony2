<?php

namespace App\Controller;

use App\Entity\Centre;
use App\Repository\CentreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class CentreJsonController extends AbstractController
{


    #[Route("/Allcentres", name: "list")]

    public function getCentre(CentreRepository $repo, SerializerInterface $serializer)
    {
        $centre = $repo->findAll();


        $json = $serializer->serialize($centre, 'json', ['groups' => "centres"]);


        return new Response($json);
    }

    #[Route("/centres/{id}", name: "centres")]
    public function CentreId($id, NormalizerInterface $normalizer, CentreRepository $repo)
    {
        $centre = $repo->find($id);
        $studentNormalises = $normalizer->normalize($centre, 'json', ['groups' => "centres"]);
        return new Response(json_encode($studentNormalises));
    }


    #[Route("addcentreJSON/new", name: "addStudentJSON")]
    public function addStudentJSON(Request $req,   NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $centre = new Centre();
        $centre->setNomCentre($req->get('NomCentre'));
        $centre->setCapaciteCentre($req->get('CapaciteCentre'));

        $centre->setLocalisation($req->get('Localisation'));
        $centre->setImg($req->get('Img'));
        $em->persist($centre);
        $em->flush();

        $jsonContent = $Normalizer->normalize($centre, 'json', ['groups' => 'centres']);
        return new Response(json_encode($jsonContent));
    }

    #[Route("updatecentreJSON/{id}", name: "updatecentreSON")]
    public function updateCentreJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository(Centre::class)->find($id);
        $centre->setNomCentre($req->get('NomCentre'));
        $centre->setCapaciteCentre($req->get('CapaciteCentre'));

        $centre->setLocalisation($req->get('Localisation'));
        $centre->setImg($req->get('Img'));

        $em->flush();

        $jsonContent = $Normalizer->normalize($centre, 'json', ['groups' => 'centres']);
        return new Response("Student updated successfully " . json_encode($jsonContent));
    }

    #[Route("deletecentreJSON/{id}", name: "deletecentreJSON")]
    public function deletecentreJSON(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository(Centre::class)->find($id);
        $em->remove($centre);
        $em->flush();
        $jsonContent = $Normalizer->normalize($centre, 'json', ['groups' => 'centres']);
        return new Response("Student deleted successfully " . json_encode($jsonContent));
    }
}