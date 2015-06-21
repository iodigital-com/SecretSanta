<?php

namespace Intracto\SecretSantaBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

        $response = $exportService->exportCSV($entryService->getAllUniqueEmailsIterator());
        return $response;
    }
}
