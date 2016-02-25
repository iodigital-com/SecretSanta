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
        $reportQuery = $this->get('intracto_secret_santa.reporting');
        $featured_years = $reportQuery->getFeaturedYears();
        $current_year = $request->get('year');

        if ($current_year != NULL && $current_year != 'all') {
            $dataPool = $reportQuery->getPoolReport($current_year);
        } else {
            $dataPool = $reportQuery->getFullPoolReport();
        }

        return $data = [
            'current_year' => $current_year,
            'dataPool' => $dataPool,
            'featured_years' => $featured_years
        ];
    }
}