<?php

namespace App\Service;

use App\Entity\Stagiaire;
use Symfony\Component\HttpFoundation\Response;
use Spipu\Html2Pdf;

class PdfGeneratorService
{
    private $twig;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
    }

    public function generatePdf(Stagiaire $stagiaire): Response
    {
        $html = $this->twig->render('stagiaire/pdf.html.twig', [
            'stagiaire' => $stagiaire,
        ]);

        $pdf = new \HTML2PDF('P', 'A4', 'fr');
        $pdf->writeHTML($html);

        $pdfContent = $pdf->output('', 'S');

        $response = new Response($pdfContent);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'inline; filename=stagiaire.pdf');

        return $response;
    }
}
