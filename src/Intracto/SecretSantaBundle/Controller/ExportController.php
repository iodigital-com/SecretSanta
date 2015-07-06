<?php

namespace Intracto\SecretSantaBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @Route("/export")
 */
class ExportController extends Controller
{
    /**
     * @Route("/entries", name="export_entries")
     */
    function entriesAction()
    {
        $entryService = $this->get('intracto_secret_santa.entry_service');
        $exportService = $this->get('intracto_secret_santa.service.export');

        $data = $entryService->getAllUniqueEmails();
        $response = $exportService->exportCSV($data);
        return $response;
    }
}
