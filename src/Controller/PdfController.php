<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Service\PdfGeneratorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PdfController extends AbstractController
{
    private $pdfGenerator;

    public function __construct(PdfGeneratorService $pdfGeneratorService)
    {
        $this->pdfGenerator = $pdfGeneratorService;
    }

    public function generatePdf(Stagiaire $stagiaire): Response
    {
        return $this->pdfGenerator->generatePdf($stagiaire);
    }
}
