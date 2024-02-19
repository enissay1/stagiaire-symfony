<?php
// src/Controller/PdfController.php
namespace App\Controller;

use App\Entity\Stagiaire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Spipu\Html2Pdf;

class PdfController extends AbstractController
{
    private $pdfGeneratorService;
    private $twig;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
    }

    public function generatePdf(Stagiaire $stagiaire): Response
    {
        // Rendez le fichier Twig pour obtenir le contenu HTML
        $html = $this->twig->render('stagiaire/pdf.html.twig', [
            'stagiaire' => $stagiaire,
        ]);

        $pdf = new \HTML2PDF('P', 'A4', 'fr');

        // Ajouter le contenu HTML au PDF
        $pdf->writeHTML($html);

        // Output the PDF to the browser
        $pdfContent = $pdf->output('', 'S');

        // Créer une réponse Symfony avec le contenu PDF
        $response = new Response($pdfContent);

        // Définissez les en-têtes pour indiquer qu'il s'agit d'un fichier PDF
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'inline; filename=stagiaire.pdf');

        return $response;
    }
}
