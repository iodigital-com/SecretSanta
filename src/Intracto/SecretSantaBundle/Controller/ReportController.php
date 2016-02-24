<?php

namespace Intracto\SecretSantaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Intracto\SecretSantaBundle\Entity\Pool;
use Intracto\SecretSantaBundle\Entity\Entry;
use Symfony\Component\HttpFoundation\Request;

class ReportController extends Controller
{
    /**
     * @Route("/report", name="report")
     * @Template()
     */
    public function reportAction(Request $request)
    {
        $reportingServices = $this->get('intracto_secret_santa.report.reporting');

        if (isset ($_GET['year']) && $_GET['year'] != "all") {
            $current_year = $_GET['year'];
            $dataPool = $reportingServices->getPullReport($current_year);
        } else {
            $current_year = 'all';
            $dataPool = $reportingServices->getFullPullReport();
        }

        return $data = [
            "current_year" => $current_year,
            "dataPool" => $dataPool
        ];
    }
}