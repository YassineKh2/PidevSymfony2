<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ColorArticle;
use App\Entity\SizeArticle;
use App\Form\ArticleType;
use App\Form\RechercheFormType;
use App\Repository\ArticleRepository;
use App\Repository\ColorArticleRepository;
use App\Repository\SizeArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article_index', methods: ['GET', 'POST'])]
    public function index(Request $request,ArticleRepository $articleRepository,PaginatorInterface $paginator): Response
    {
        $form = $this->createForm(RechercheFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->getData()['Recherche'] != '') {
            $articles =  $paginator->paginate($articleRepository->FindArticle($form->getData()['Recherche']), $request->query->getInt('page', 1),5);
            return $this->renderForm('article/index.html.twig', [
                'articles' => $articles,
                'form' => $form
            ]);
        }
        $articles =  $paginator->paginate($articleRepository->findAll(), $request->query->getInt('page', 1),5);
        return $this->renderForm('article/index.html.twig', [
            'articles' => $articles,
            'form' => $form
        ]);
    }

    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArticleRepository $articleRepository,SizeArticleRepository $sizeArticleRepository,ColorArticleRepository $colorArticleRepository): Response
    {


        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        $size = $form->get('Size');
        $color = $form->get('Color');

        $array = explode(' ', $color->getData());


        if ($form->isSubmitted() && $form->isValid()) {

                $pictureFile = $form->get('ImageArticle')->getData();
                if ($pictureFile) {
                    $pictureFileName = uniqid() . '.' . $pictureFile->guessExtension();
                    $pictureFile->move(
                        $this->getParameter('pictures_directory'),
                        $pictureFileName
                    );
                    $pictureFileName = 'Back/images/CategorieImages/' . $pictureFileName;
                    $article->setImageArticle($pictureFileName);
                }
                else
                    $article->setImageArticle("Back/images/CategorieImages/NoImageFound.png");


            if($color != '') {
                $array = explode(' ', $color->getData());
                foreach ($array as $colorA) {
                    $colorArtilce = new ColorArticle();
                    $colorArtilce->setColor($colorA);
                    $colorArtilce->setArticle($article);
                    $article->addColorArticle($colorArtilce);

                }

            }
            if($size != '') {
                $array = explode(' ', $size->getData());
                foreach ($array as $sizeA) {
                    $sizeArtilce = new SizeArticle();
                    $sizeArtilce->setSize($sizeA);
                    $sizeArtilce->setArticle($article);
                    $article->addSizeArticle($sizeArtilce);
                }

            }

            $articleRepository->save($article, true);

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        $size = $form->get('Size');
        $color = $form->get('Color');

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('ImageArticle')->getData();
            if ($pictureFile) {
                $pictureFileName = uniqid() . '.' . $pictureFile->guessExtension();
                $pictureFile->move(
                    $this->getParameter('pictures_directory'),
                    $pictureFileName
                );
                $pictureFileName = 'Back/images/CategorieImages/' . $pictureFileName;
                $article->setImageArticle($pictureFileName);
            }


            if($color != '') {
                $article->removeAllColorArticles();
                $array = explode(' ', $color->getData());
                foreach ($array as $colorA) {
                    $colorArtilce = new ColorArticle();
                    $colorArtilce->setColor($colorA);
                    $colorArtilce->setArticle($article);
                    $article->addColorArticle($colorArtilce);

                }

            }
            if($size != '') {
                $article->removeAllSizeArticles();
                $array = explode(' ', $size->getData());
                foreach ($array as $sizeA) {
                    $sizeArtilce = new SizeArticle();
                    $sizeArtilce->setSize($sizeA);
                    $sizeArtilce->setArticle($article);
                    $article->addSizeArticle($sizeArtilce);
                }

            }

            $articleRepository->save($article, true);

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $articleRepository->remove($article, true);
        }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }

}
