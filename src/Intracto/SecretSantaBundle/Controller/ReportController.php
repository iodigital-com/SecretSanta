<?php

namespace Intracto\SecretSantaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ReportController extends Controller
{
    /**
     * @Route("/report/{year}", defaults={"year" = "all"}, name="report")
     * @Template()
     */
    public function reportAction($year)
    {
        $analyticsQuery = $this->get('intracto_secret_santa.analytics');
        $report = $this->get('intracto_secret_santa.report');
        $comparison = $this->get('intracto_secret_santa.season_comparison');

        try {
            if ($year != 'all') {
                $dataPool = $report->getPoolReport($year);
                $differenceDataPool = $comparison->getComparison($year);
            } else {
                $dataPool = $report->getPoolReport();
            }
        } catch (\Exception $e) {
            $dataPool = [];
            $differenceDataPool = [];
        }

        try {
            if ($year != 'all') {
                $googleDataPool = $analyticsQuery->getAnalyticsReport($year);
            } else {
                $googleDataPool = $analyticsQuery->getAnalyticsReport();
            }
        } catch (\Exception $e) {
            $googleDataPool = [];
        }

        $data = [
            'current_year' => $year,
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