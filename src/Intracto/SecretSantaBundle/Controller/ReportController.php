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
        $analyticsQuery = $this->get('intracto_secret_santa.analytics');
        $report = $this->get('intracto_secret_santa.report');
        $comparison = $this->get('intracto_secret_santa.season_comparison');
        $currentYear = $request->get('year', 'all');

        try {
            if ($currentYear != 'all') {
                $dataPool = $report->getPoolReport($currentYear);
                $differenceDataPool = $comparison->getComparison($currentYear);
            } else {
                $dataPool = $report->getPoolReport();
            }
        } catch (\Exception $e) {
            $dataPool = [];
        }

        try {
            if ($currentYear != 'all') {
                $googleDataPool = $analyticsQuery->getAnalyticsReport($currentYear);
            } else {
                $googleDataPool = $analyticsQuery->getAnalyticsReport();
            }
        } catch (\Exception $e) {
            $googleDataPool = [];
        }

        $data =  [
            'current_year' => $currentYear,
            'data_pool' => $dataPool,
            'featured_years' => $this->get('intracto_secret_santa.featured_years')->getFeaturedYears(),
            'google_data_pool' => $googleDataPool,
        ];

        if(isset($differenceDataPool)) {
            $data['difference_data_pool'] = $differenceDataPool;
        }

        return $data;
    }
}