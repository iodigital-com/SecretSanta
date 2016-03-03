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
        $analyticsQuery = $this->get('intracto_secret_santa.analytics');
        $featuredYears = $reportQuery->getFeaturedYears();
        $currentYear = $request->get('year', 'all');

        try {
            if ($currentYear != 'all') {
                $dataPool = $reportQuery->getPoolReport($currentYear);
                $googleDataPool = $analyticsQuery->getAnalyticsReport($currentYear);
            } else {
                $dataPool = $reportQuery->getFullPoolReport();
                $googleDataPool = $analyticsQuery->getFullAnalyticsReport();
            }
        } catch (\Exception $e) {
            $currentYear = [];
            $dataPool = [];
            $googleDataPool = [];
        }

        return [
            'current_year' => $currentYear,
            'data_pool' => $dataPool,
            'featured_years' => $featuredYears,
            'google_data_pool' => $googleDataPool,
        ];
    }
}