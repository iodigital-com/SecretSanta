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
        $reportingHelper = $this->get('intracto_secret_santa.reporting');

        $current_year = $request->get('year');

        if ($current_year != NULL && $current_year != 'all') {
            $dataPool = $reportingHelper->getPullReport($current_year);

            $featured_years = [];
            foreach ($dataPool['featured_years'] as $d) {
                array_push($featured_years, $d['featured_year']);
            }

            if (!in_array($current_year, $featured_years)) {
                throw $this->createNotFoundException('Data over dit jaartal zijn niet beschikbaar.');
            }
        } else {
            $dataPool = $reportingHelper->getFullPullReport();
        }

        return $data = [
            'current_year' => $current_year,
            'dataPool' => $dataPool
        ];
    }
}