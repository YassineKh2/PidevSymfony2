<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class ArticleJsonController extends AbstractController
{

    #[Route('/AllArticleJson', name: 'app_Article_showJson', methods: ['GET', 'POST'])]
    public function ShowArticleJson(ArticleRepository $ArticleRepository,NormalizerInterface $normalizer)
    {

        $Articles = $ArticleRepository->findAll();

        $article = new Article();

        $articleArray = array();

        foreach ($Articles as $article) {
            $articleData = [
                'id' => $article->getid(),
                'Categorie' => [
                    'id' => $article->getCategorie()->getId(),
                    'NomCategorie' => $article->getCategorie()->getNomCategorie(),
                    'ImageCategorie' => $article->getCategorie()->getImageCategorie()
                ],
                'NomArticle' => $article->getNomArticle(),
                'PrixArticle' => $article->getPrixArticle(),
                'QuantiteArticle' => $article->getQuantiteArticle(),
                'ImageArticle' => $article->getImageArticle(),
                'ArticleDiscription' => $article->getArticleDiscription(),
                'RemisePourcentageArticle' => $article->getRemisePourcentageArticle(),

            ];

            array_push($articleArray, $articleData);
        }





        return new JsonResponse($articleArray);
    }

    #[Route('/FindArticleJson/{id}', name: 'app_Article_findJson', methods: ['GET', 'POST'])]
    public function FindArticleJson(ArticleRepository $ArticleRepository,NormalizerInterface $normalizer,$id)
    {

        $Articles = $ArticleRepository->find($id);

        $Articles = [
            'id' => $Articles->getid(),
            'Categorie' => [
                'id' => $Articles->getCategorie()->getId(),
                'NomCategorie' => $Articles->getCategorie()->getNomCategorie(),
                'ImageCategorie' => $Articles->getCategorie()->getImageCategorie()
            ],
            'NomArticle' => $Articles->getNomArticle(),
            'PrixArticle' => $Articles->getPrixArticle(),
            'QuantiteArticle' => $Articles->getQuantiteArticle(),
            'ImageArticle' => $Articles->getImageArticle(),
            'ArticleDiscription' => $Articles->getArticleDiscription(),
            'RemisePourcentageArticle' => $Articles->getRemisePourcentageArticle(),

        ];


        return new JsonResponse($Articles);
    }

    #[Route('/AddArticleJson', name: 'app_Article_AddJson', methods: ['GET', 'POST'])]
    public function AddArticleJson(ArticleRepository $ArticleRepository,NormalizerInterface $normalizer,Request $request,CategorieRepository $categorieRepository)
    {

        $Article = new Article();

        $Article->setNomArticle($request->get('NomArticle'));
        $Article->setPrixArticle($request->get('PrixArticle'));
        $Article->setQuantiteArticle($request->get('QuantiteArticle'));
        $Article->setArticleDiscription($request->get('ArticleDiscription'));
        $Article->setRemisePourcentageArticle($request->get('RemisePourcentageArticle'));
        $Article->setImageArticle($request->get('ImageArticle'));
        $categorie = $categorieRepository->find($request->get('IdCategorie'));
        $Article->setCategorie($categorie);


        $ArticleRepository->save($Article,true);


        $jsoncontent = $normalizer->normalize($Article,'json',['groups' => "Article"]);


        return new Response(json_encode($jsoncontent));
    }

    #[Route('/ModifyArticleJson/{id}', name: 'app_Article_ModifyJson', methods: ['GET', 'POST'])]
    public function ModifyArticleJson(ArticleRepository $ArticleRepository,NormalizerInterface $normalizer,Request $request,$id,CategorieRepository $categorieRepository)
    {

        $Article = new Article();

        $Article = $ArticleRepository->find($id);
        $Article->setNomArticle($request->get('NomArticle'));
        $Article->setPrixArticle($request->get('PrixArticle'));
        $Article->setQuantiteArticle($request->get('QuantiteArticle'));
        $Article->setArticleDiscription($request->get('ArticleDiscription'));
        $Article->setRemisePourcentageArticle($request->get('RemisePourcentageArticle'));
        $Article->setImageArticle($request->get('ImageArticle'));
        $categorie = $categorieRepository->find($request->get('IdCategorie'));
        $Article->setCategorie($categorie);



        $ArticleRepository->save($Article,true);


        $jsoncontent = $normalizer->normalize($Article,'json',['groups' => "Article"]);


        return new Response(json_encode($jsoncontent));
    }

    #[Route('/DeleteArticleJson/{id}', name: 'app_Article_DeleteJson', methods: ['GET', 'POST'])]
    public function DeleteArticleJson(ArticleRepository $ArticleRepository,NormalizerInterface $normalizer,$id)
    {
        $Article = new Article();
        $Article = $ArticleRepository->find($id);

        // Testing if Article exist
        if ($Article == null)
            return new JsonResponse("Id erroné");


        $ArticleRepository->remove($Article,true);
        $jsoncontent = $normalizer->normalize($Article,'json',['groups' => "Article"]);

        return new Response("La Article a etait supprimer avec success".json_encode($jsoncontent));
    }
    #[Route('/ModifyArticleJsonQuantite/{id}', name: 'app_Article_ModifyJsonQuantite', methods: ['GET', 'POST'])]
    public function ModifyArticleJsonQuantite(ArticleRepository $ArticleRepository,NormalizerInterface $normalizer,Request $request,$id,CategorieRepository $categorieRepository)
    {

        $Article = new Article();

        $Article = $ArticleRepository->find($id);
        $Article->setQuantiteArticle($request->get('QuantiteArticle'));



        $ArticleRepository->save($Article,true);


        $jsoncontent = $normalizer->normalize($Article,'json',['groups' => "Article"]);


        return new Response(json_encode($jsoncontent));
    }

}