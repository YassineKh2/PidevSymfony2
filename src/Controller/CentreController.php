<?php

namespace App\Controller;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Centre;
use App\Form\CentreType;
use App\Repository\CentreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

#[Route('/centre')]
class CentreController extends AbstractController
{

    #[Route('/', name: 'app_centre_index', methods: ['GET'])]
    public function index(CentreRepository $centreRepository): Response
    {
        return $this->render('centre/index.html.twig', [
            'centres' => $centreRepository->findAll(),
        ]);
    }
    #[Route('/front', name: 'centre_index3', methods: ['GET'])]
    public function index3(Request $request, CentreRepository $centreRepository, PaginatorInterface $paginator): Response
    {
        $centres = $paginator->paginate(
            $centreRepository->findAll(),
            $request->query->getInt('page', 1),
            3// Nombre d'éléments affichés par page
        );

        return $this->render('centre/CentreFront.html.twig', [
            'centres' => $centres,
        ]);
    }


    public function searchAction(Request $request, CentreRepository $centreRepository,FormBuilderInterface $formBuilder)
    {
        $form = $formBuilder->createBuilder()
            ->add('search', SearchType::class)
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $term = $form->getData()['search'];

            $centres = $centreRepository->search($term);

            return $this->render('search_results.html.twig', [
                'centres' => $centres,
            ]);
        }

        return $this->render('search.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'app_centre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CentreRepository $centreRepository): Response
    {
        $centre = new Centre();
        $form = $this->createForm(CentreType::class, $centre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('img')->getData();
            if ($pictureFile) {
                $pictureFileName = uniqid() . '.' . $pictureFile->guessExtension();
                $pictureFile->move(
                    $this->getParameter('pictures_directory_Centre'),
                    $pictureFileName
                );
                $pictureFileName = 'Front/Centre/Image/' . $pictureFileName;
                $centre->setimg($pictureFileName);
            }
            else
                $centre->setimg("Front/Centre/Image/NoImageFound.png");
            $centreRepository->save($centre, true);

            return $this->redirectToRoute('app_centre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('centre/new.html.twig', [
            'centre' => $centre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_centre_show', methods: ['GET'])]
    public function show(Centre $centre): Response
    {
        return $this->render('centre/show.html.twig', [
            'centre' => $centre,
        ]);
    }
    #[Route('/{id}/plan', name: 'app_centre_show1', methods: ['GET'])]
    public function show1(Centre $centre): Response
    {
        return $this->render('centre/test.html.twig', [
            'centre' => $centre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_centre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Centre $centre, CentreRepository $centreRepository): Response
    {
        $form = $this->createForm(CentreType::class, $centre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('img')->getData();
            if ($pictureFile) {
                $pictureFileName = uniqid() . '.' . $pictureFile->guessExtension();
                $pictureFile->move(
                    $this->getParameter('pictures_directory_Centre'),
                    $pictureFileName
                );
                $pictureFileName = 'Front/Centre/Image/' . $pictureFileName;
                $centre->setimg($pictureFileName);
            }
            $centreRepository->save($centre, true);

            return $this->redirectToRoute('app_centre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('centre/edit.html.twig', [
            'centre' => $centre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_centre_delete', methods: ['POST'])]
    public function delete(Request $request, Centre $centre, CentreRepository $centreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$centre->getId(), $request->request->get('_token'))) {
            $centreRepository->remove($centre, true);
        }

        return $this->redirectToRoute('app_centre_index', [], Response::HTTP_SEE_OTHER);
    }


}
