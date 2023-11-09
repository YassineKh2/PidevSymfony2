<?php

namespace App\Controller;

use App\Entity\Publication;

use App\Form\PublicationType;
use App\Repository\PublicationRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;


#[Route('/forumjson')]
class PublicationJsonController extends AbstractController
{
    #[Route('/all', name: 'app_publication_index_json')]
    public function index(PublicationRepository $publicationRepository, SerializerInterface $serializer ): Response
    {
        $publications = $publicationRepository->findAll();
        $json = $serializer->serialize($publications, 'json',['groups' => 'publications']);
        return new Response($json);
    }

    #[Route('/new', name: 'app_publication_new_json')]
    public function new(Request $request, SerializerInterface $serializer ,NormalizerInterface $normalizer): Response
    {
        $em = $this->getDoctrine()->getManager();
        $publication = new Publication();
        $publication->setContenuPublication($request->get('contenu_publication'));
        $publication->setDatePublication(new \DateTime("now"));
        $publication->setIsApproved($request->get('is_approved'));

        $em->persist($publication);
        $em->flush();


$json = $normalizer->normalize($publication, 'json', ['groups' => 'publications']);
            return new Response(json_encode($json));

        // $user=$this->getUser();
        // $publication->setUser($user);

    }

    #[Route('/{id}', name: 'app_publication_show_json')]
    public function show(Publication $publication, SerializerInterface $serializer): Response
    {
        $json = $serializer->serialize($publication, 'json',['groups' => 'publications']);
        return new Response($json);
    }

    #[Route('/edit/{id}', name: 'app_publication_edit_json')]
    public function edit(Request $request, Publication $publication, SerializerInterface $serializer,$id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $publication=$em->getRepository(Publication::class)->find($id);
        $publication->setContenuPublication($request->get('contenu_publication'));
        $publication->setDatePublication(new \DateTime("now"));

        $em->flush();

        $json = $serializer->serialize($publication, 'json',['groups' => 'publications']);
        return new Response($json);
    }

    #[Route('/delete/{id}', name: 'app_publication_delete_json')]
    public function delete(Request $request, Publication $publication, SerializerInterface $serializer, $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $publication=$em->getRepository(Publication::class)->find($id);
        $em->remove($publication);
        $em->flush();

        $json = $serializer->serialize($publication, 'json',['groups' => 'publications']);
        return new Response($json);
    }


}