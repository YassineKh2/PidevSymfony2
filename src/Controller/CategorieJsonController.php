<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class CategorieJsonController extends AbstractController
{

    #[Route('/AllCategorieJson', name: 'app_categorie_showJson', methods: ['GET', 'POST'])]
    public function ShowCategorieJson(CategorieRepository $categorieRepository,NormalizerInterface $normalizer)
    {


        $categories = $categorieRepository->findAll();

        $arraycateg = array();

        foreach ($categories as $categ) {
            $articlesArray = array();
            foreach ($categ->getArticles() as $article) {
                $articleData = [
                    'id' => $article->getId(),
                    'NomArticle' => $article->getNomArticle(),
                    'PrixArticle' => $article->getPrixArticle(),
                    'QuantiteArticle' => $article->getQuantiteArticle(),
                    'ImageArticle' => $article->getImageArticle(),
                    'ArticleDiscription' => $article->getArticleDiscription(),
                    'RemisePourcentageArticle' => $article->getRemisePourcentageArticle(),
                ];

                array_push($articlesArray, $articleData);
            }

            $categData = [
                'id' => $categ->getId(),
                'NomCategorie' => $categ->getNomCategorie(),
                'ImageCategorie' => $categ->getImageCategorie(),
                'Articles' => $articlesArray,
            ];

            array_push($arraycateg, $categData);
        }

        return new JsonResponse($arraycateg);
    }

    #[Route('/FindCategorieJson/{id}', name: 'app_categorie_findJson', methods: ['GET', 'POST'])]
    public function FindCategorieJson(CategorieRepository $categorieRepository,NormalizerInterface $normalizer,$id)
    {

        $categories = $categorieRepository->find($id);


        $arraycateg = array();


        $articlesArray = array();
        foreach ($categories->getArticles() as $article) {
            $articleData = [
                'id' => $article->getId(),
                'NomArticle' => $article->getNomArticle(),
                'PrixArticle' => $article->getPrixArticle(),
                'QuantiteArticle' => $article->getQuantiteArticle(),
                'ImageArticle' => $article->getImageArticle(),
                'ArticleDiscription' => $article->getArticleDiscription(),
                'RemisePourcentageArticle' => $article->getRemisePourcentageArticle(),
            ];

            array_push($articlesArray, $articleData);
        }

        $categData = [
            'id' => $categories->getId(),
            'NomCategorie' => $categories->getNomCategorie(),
            'ImageCategorie' => $categories->getImageCategorie(),
            'Articles' => $articlesArray,
        ];




        return new JsonResponse($categData);


    }

    #[Route('/AddCategorieJson', name: 'app_categorie_AddJson', methods: ['GET', 'POST'])]
    public function AddCategorieJson(CategorieRepository $categorieRepository,NormalizerInterface $normalizer,Request $request)
    {

        $categorie = new Categorie();

        $categorie->setNomCategorie($request->get('NomCategorie'));
        $categorie->setImageCategorie($request->get('ImageCategorie'));


        $categorieRepository->save($categorie,true);


        $jsoncontent = $normalizer->normalize($categorie,'json',['groups' => "Categorie"]);


        return new Response(json_encode($jsoncontent));
    }

    #[Route('/ModifyCategorieJson/{id}', name: 'app_categorie_ModifyJson', methods: ['GET', 'POST'])]
    public function ModifyCategorieJson(CategorieRepository $categorieRepository,NormalizerInterface $normalizer,Request $request,$id)
    {

        $categorie = new Categorie();

        $categorie = $categorieRepository->find($id);
        $categorie->setNomCategorie($request->get('NomCategorie'));
        $categorie->setImageCategorie($request->get('ImageCategorie'));


        $categorieRepository->save($categorie,true);


        $jsoncontent = $normalizer->normalize($categorie,'json',['groups' => "Categorie"]);


        return new Response(json_encode($jsoncontent));
    }

    #[Route('/DeleteCategorieJson/{id}', name: 'app_categorie_DeleteJson', methods: ['GET', 'POST'])]
    public function DeleteCategorieJson(CategorieRepository $categorieRepository,NormalizerInterface $normalizer,$id)
    {
        $categorie = new Categorie();
        $categorie = $categorieRepository->find($id);

        // Testing if categorie exist
        if ($categorie == null)
            return new JsonResponse("Id erroné");


        $categorieRepository->remove($categorie,true);
        $jsoncontent = $normalizer->normalize($categorie,'json',['groups' => "Categorie"]);

        return new Response("La categorie a etait supprimer avec success".json_encode($jsoncontent));
    }

}
