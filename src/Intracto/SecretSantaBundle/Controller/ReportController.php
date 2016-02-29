<?php

namespace Intracto\SecretSantaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
        $featuredYears = $reportQuery->getFeaturedYears();
        $currentYear = $request->get('year', 'all');

        try {
            if ($currentYear != 'all') {
                $dataPool = $reportQuery->getPoolReport($currentYear);
            } else {
                $dataPool = $reportQuery->getFullPoolReport();
            }
        } catch (\Exception $e) {
            $currentYear = [];
            $dataPool = [];
        }

        var_dump($dataPool['linechart_entries']);

        return [
            'current_year' => $currentYear,
            'data_pool' => $dataPool,
            'featured_years' => $featuredYears,
        ];
    }
}