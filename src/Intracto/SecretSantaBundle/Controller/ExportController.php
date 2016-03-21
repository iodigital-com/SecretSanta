<?php

namespace Intracto\SecretSantaBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intracto\SecretSantaBundle\Query\Season;

/**
 * @Route("/export")
 */
class ExportController extends Controller
{
    /**
     * @Route("/admins", name="export_admins")
     */
    public function exportPoolAdminMailsAction()
    {
        $entryService = $this->get('intracto_secret_santa.entry');

        $previousYear = date('Y', strtotime('-1 year'));
        $season = new Season($previousYear);

        $data = $entryService->fetchAdminEmailsForExport($season);

        return $data;
    }

    /**
     * @Route("/participants", name="export_participants")
     */
    public function exportPoolParticipantMailsAction()
    {
        $entryService = $this->get('intracto_secret_santa.entry');

        $previousYear = date('Y', strtotime('-1 year'));
        $season = new Season($previousYear);

        $data = $entryService->fetchParticipantEmailsForExport($season);

        return $data;
    }
}
