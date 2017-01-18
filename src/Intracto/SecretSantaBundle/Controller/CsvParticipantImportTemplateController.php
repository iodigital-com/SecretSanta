<?php

namespace Intracto\SecretSantaBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CsvParticipantImportTemplateController extends Controller
{
    /**
     * @Route("/download-csv-template", name="download_csv_template")
     */
    public function downloadAction()
    {
        $path = $this->get('kernel')->getRootDir().'/../src/Intracto/SecretSantaBundle/Resources/public/downloads/templateCSVSecretSantaOrganizer.csv';
        $content = file_get_contents($path);

        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment;filename="templateCSVSecretSantaOrganizer.csv"');

        $response->setContent($content);

        return $response;
    }
}
