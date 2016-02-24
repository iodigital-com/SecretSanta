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

        $current_year = $request->get('year');

        if ($request->get('year') != NULL && $request->get('year') != 'all') {
            $dataPool = $reportingServices->getPullReport($current_year);
        } else {
            $dataPool = $reportingServices->getFullPullReport();
        }

        return $data = [
            'current_year' => $current_year,
            'dataPool' => $dataPool
        ];
    }
}