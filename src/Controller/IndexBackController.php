<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



class IndexBackController extends AbstractController
{
    #[Route('/indexBack', name: 'app_index_back')]
    public function index(): Response
    {
        return $this->render('baseBack.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/MakeExeclCategorie', name: 'app_index_MakeExcelCategorie')]
    public function MakeExcelCategorire(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();


        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'NomCategorie');
        $sheet->setCellValue('C1', 'Nombre de article');


        $rowNumber = 2;
        foreach ($categories as $categorie) {
            $sheet->setCellValue('A' . $rowNumber, $categorie->getId());
            $sheet->setCellValue('B' . $rowNumber, $categorie->getNomCategorie());
            $sheet->setCellValue('C' . $rowNumber, $categorie->getArticles()->count());
            $rowNumber++;
        }


        $writer = new Xlsx($spreadsheet);


        $response = new StreamedResponse();


        $filename = tempnam(sys_get_temp_dir(), 'categories');
        $writer->save($filename);


        $response = new BinaryFileResponse($filename);


        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'Categories.xlsx'
        );
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');


        return $response;
    }

    #[Route('/MakeExeclArticle', name: 'app_index_MakeExcelArticle')]
    public function MakeExcelArticle(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();
        $spreadsheet = new Spreadsheet();

// Set the active sheet
        $sheet = $spreadsheet->getActiveSheet();

// Set the headers
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Nom');
        $sheet->setCellValue('C1', 'Prix ');
        $sheet->setCellValue('D1', 'Quantite');
        $sheet->setCellValue('E1', 'RemisePourcentageArticle');
        $sheet->setCellValue('F1', 'Categorie');

// Loop through the categories and set the values
        $rowNumber = 2;
        foreach ($articles as $article) {
            $sheet->setCellValue('A' . $rowNumber, $article->getId());
            $sheet->setCellValue('B' . $rowNumber, $article->getNomArticle());
            $sheet->setCellValue('C' . $rowNumber, $article->getPrixArticle());
            $sheet->setCellValue('D' . $rowNumber, $article->getQuantiteArticle());
            $sheet->setCellValue('E' . $rowNumber, $article->getRemisePourcentageArticle());
            $sheet->setCellValue('F' . $rowNumber, $article->getCategorie()->getNomCategorie());
            $rowNumber++;
        }

// Create a new Xlsx object and write the Spreadsheet to it
        $writer = new Xlsx($spreadsheet);

// Create a response object
        $response = new StreamedResponse();

// Create a temporary file to write the Excel file to
        $filename = tempnam(sys_get_temp_dir(), 'articles');
        $writer->save($filename);

// Create a new BinaryFileResponse object
        $response = new BinaryFileResponse($filename);

// Set the content type and headers
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'Articles.xlsx'
        );
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

// Return the response object
        return $response;
    }

}
