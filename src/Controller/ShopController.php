<?php

namespace App\Controller;

use App\Entity\Coupon;
use App\Entity\Payment;
use App\Entity\Rating;
use App\Form\CategorieType;
use App\Form\CouponFormType;
use App\Form\RatingFormType;
use App\Repository\CategorieRepository;
use App\Repository\ArticleRepository;
use App\Repository\CouponRepository;
use App\Repository\PaymentRepository;
use App\Repository\RatingRepository;
use App\Repository\UserRepository;
use App\Repository\UtilisateurRepository;
use Knp\Component\Pager\PaginatorInterface;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bridge\Twig\Mime\WrappedTemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Routing\Annotation\Route;
use Spipu\Html2Pdf\Html2Pdf;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;


class ShopController extends AbstractController
{
    #[Route('/shop', name: 'app_shop')]
    public function index(CategorieRepository $categorieRepository,ArticleRepository $ArticleRepository): Response
    {
        $token = $this->get('security.token_storage')->getToken();
        if ($token) {
            $user = $token->getUser();
            if ($user) {
                $userId = $user->getId();
            }
        }




        return $this->render('shop/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
            'articles' => $ArticleRepository->FindBestALLTimeSellers(),
            'articlesFirst8' => $ArticleRepository->Find10First(),
        ]);
    }
    #[Route('/shop/categorie/{id}', name: 'app_articles')]
    public function afficherArticles(ArticleRepository $ArticleRepository,$id, PaginatorInterface $paginator,Request $request): Response
    {
        $articles =  $paginator->paginate($ArticleRepository->FindArticleByIdCategorie($id), $request->query->getInt('page', 1),5);
        return $this->render('shop/showLesArticles.html.twig', [
            'articles' => $articles,
        ]);
    }
    #[Route('/shop/article/{id}', name: 'app_article')]
    public function afficherArticle(ArticleRepository $ArticleRepository,$id,Request $request,RatingRepository $ratingRepository,UserRepository $userRepository): Response
    {
        $token = $this->get('security.token_storage')->getToken();
        if ($token) {
            $user = $token->getUser();
            if ($user) {
                $userId = $user->getId();
            }
        }

        $rating = new Rating();
        $user = $userRepository->find($userId);
        $article = $ArticleRepository->findBy(array('id' => $id))[0];
        $AvRating = $ratingRepository->findByAveRatingById($id);

        $form = $this->createForm(RatingFormType::class, $rating);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $currentDate = new \DateTime();
            $rating->setRatingDate($currentDate);
            $rating->setUser($user);
            $rating->setArticle($ArticleRepository->Find($id));
            $ratingRepository->save($rating,true);

            return $this->render('shop/showSingleArticle.html.twig', [
                'form' => $form->createView(),
                'article' => $article,
                'articles' => $ArticleRepository->FindArticleByIdCategorie($article->getCategorie()->getId()),
                'AvRating' => $AvRating[0][1]
            ]);
        }
        return $this->render('shop/showSingleArticle.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
            'articles' => $ArticleRepository->FindArticleByIdCategorie($article->getCategorie()->getId()),
            'AvRating' => $AvRating[0][1]
        ]);
    }
    #[Route('/shop/article/ajouter/{id}/{quantity}', name: 'app_article_ajouter_panier', methods: ['GET', 'POST'])]
    public function ajouterPainner(SessionInterface $session,$id,$quantity,ArticleRepository $articleRep,Request $request) : Response
    {

       // $session->clear();
       $color = $request->get("selectedcolor");
       $size = $request->get("selectedsize");
       $panier =  $session->get('panier',[]);


        $quantity = intval($quantity);
        if(!empty($panier[$id]))
             $panier[$id] +=[$quantity,$size,$color];
        else
            $panier[$id] =[$quantity,$size,$color];


        $session->set('panier',$panier);
        $article = $articleRep->find($id);
        $categorie = $article->getCategorie()->getId();
       return $this->redirectToRoute('app_articles',[
           'id' => $categorie
       ]);
    }
    #[Route('/shop/panier', name: 'app_article_afficher_panier', methods: ['GET', 'POST'])]
    public function ShowPannier(SessionInterface $session,ArticleRepository $articleRep,Request $request,CouponRepository $couponRepository) : Response
    {

        $panier =  $session->get('panier',[]);

        //dd($panier);
        $pannierWithData = [];


        foreach($panier as $id => [$quantity,$size,$color]){
            $pannierWithData[] = [
            'article' => $articleRep->find($id),
            'quantity' => $quantity,
            'size' => $size,
            'color' => $color
            ];
        }
        $form = $this->createForm(CouponFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $nom = $form->getData()['CodeCoupon'];
            $coupon = $couponRepository->FindCouponByName($nom);
            if($coupon != null) {
                $Reduction = $coupon->getPourcentageCoupon();
                return $this->renderForm("shop/panier.html.twig", [
                    "Pannier" => $pannierWithData,
                    'form' => $form,
                    'Reduction' => $Reduction
                ]);
            }
            else{

                return $this->renderForm("shop/panier.html.twig", [
                    "Pannier" => $pannierWithData,
                    'form' => $form,
                    'Reduction' => 0
                ]);
            }

        }

        return $this->renderForm("shop/panier.html.twig", [
        "Pannier" => $pannierWithData,
         'form' => $form,
            'Reduction' => 0

        ]);
    }
    #[Route('/shop/panier/remove/{id}', name: 'app_article_remove_from_panier')]
    public function RemoveArticle(SessionInterface $session,$id) : Response
    {
        $panier =  $session->get('panier',[]);
            if(!empty($panier[$id]))
                unset($panier[$id]);

        $session->set('panier',$panier);

           return $this->redirectToRoute('app_article_afficher_panier');
    }
    #[Route('/shop/share/facebook/{id}', name: 'app_article_share_facebook')]
    public function facebookShare(Request $request,$id)
    {


        $app_id = '457972143123040';
        $app_secret = '94b5c9d30ff025660c97960cafaf96f9';
        $access_token = '457972143123040|fctNX5IG9dTIUzDP6sntlHkeS-E';



        $url = 'https://www.facebook.com/dialog/feed?app_id=457972143123040&display=popup';
        $link = 'http://127.0.0.1:8000/shop/article/';

        //$url .= '&caption=wetestiin';
        $url .= '&description=wetestiin';
        $url .= '&link=' . urlencode($link);
        $url .= $id;



        return $this->redirect($url);

    }
    #[Route('/shop/share/twitter/{id}', name: 'app_article_share_twitter')]
    public function twitterShare(Request $request,$id)
    {


        $url = 'https://twitter.com/intent/tweet?text=';
        $url .= "J'adore cette article que j'ai trouver sur RehabRadar : ";
        $link = 'http://127.0.0.1:8000/shop/article/' . $id;

        $url .= $link;

        return $this->redirect($url);

    }


    /**
     * @throws TransportExceptionInterface
     * @throws Html2PdfException
     */
    #[Route('/shop/panier/FacturePdf', name: 'app_article_panier_makepdf')]
    public function generatePdfPannier(Request $request,MailerInterface $mailerIn,SessionInterface $session,ArticleRepository $articleRep,PaymentRepository $paymentRepository,UserRepository $userRepository)
    {
        $token = $this->get('security.token_storage')->getToken();
        if ($token) {
            $user = $token->getUser();
            if ($user) {
                $userId = $user->getId();
            }
        }

        $panier =  $session->get('panier',[]);
        $pannierWithData = [];


        $user = $userRepository->find($userId);
        $payment = new Payment();
        $prixTotal = 0;


        foreach($panier as $id => [$quantity,$size,$color]){
            $pannierWithData[] = [
                'article' => $articleRep->find($id),
                'quantity' => $quantity,
                'size' => $size,
                'color' => $color
            ];
        }

        $html = $this->renderView('/shop/recipt.html.twig',[
            "Pannier" => $pannierWithData,
            "NomUser" => $user->getPseudoUtilisateur()
        ]);


        foreach($panier as $id => [$quantity,$size,$color]){
            $article = $articleRep->find($id);
            $payment->addArticle($article);
            $prixTotal += $article->getPrixArticle() * $quantity;
            $article->setQuantiteArticle($article->getQuantiteArticle() - $quantity);
            $article->setSaleNumberArticle($article->getSaleNumberArticle() + $quantity);
            $articleRep->save($article,true);
        }


        $payment->setIdUser($user);
        $payment->setPrixTotal($prixTotal);

        $currentDate = new \DateTime();

        $payment->setDatePayment($currentDate);

        $pdf = new Html2Pdf();


        $pdf->writeHTML($html);


        $pdfContent = $pdf->output('', 'S');

        $userEmail = $user->getEmail();


        $paymentRepository->save($payment,true);



        $email = (new TemplatedEmail())
            ->from('rehabradar123@gmail.com')
            ->to('yassinekhemiri@ieee.org')
            ->subject('Merci pour votre achat!')
            ->text('This is a test email sent from Symfony.')
            ->htmlTemplate('shop/img.html.twig')
            ->context([
                "Pannier" => $pannierWithData,
                "NomUser" => $user->getPseudoUtilisateur()
            ]);



        $mailerIn->send($email);

        $session->clear();
        return new Response($pdfContent, 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="facture.pdf"',
    ]);
    }

    #[Route('/shop/categorie/{id}/{price}', name: 'app_articles_byPrice')]
    public function FilterByPrice(ArticleRepository $ArticleRepository,$id, PaginatorInterface $paginator,Request $request,$price): Response
    {
        $articles =  $paginator->paginate($ArticleRepository->FilterbyPriceWithPrice($price,$id), $request->query->getInt('page', 1),5);
        return $this->render('shop/showLesArticles.html.twig', [
            'articles' => $articles,
        ]);
    }

}
