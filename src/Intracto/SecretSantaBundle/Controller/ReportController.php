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

        if ($reportQueryResult = $this->get('cache')->fetch('data' . $year)) {
            $cache = unserialize($reportQueryResult);

            $data = [
                'current_year' => $year,
                'data_pool' => $cache['data_pool'],
                'featured_years' => $cache['featured_years'],
                'google_data_pool' => $cache['google_data_pool'],
            ];

            if (isset($cache['difference_data_pool'])) {
                $data['difference_data_pool'] = $cache['difference_data_pool'];
            }

            return $data;
        }

        try {
            if ($year != 'all') {
                $dataPool = $report->getPoolReport($year);
            } else {
                $dataPool = $report->getPoolReport();
            }
        } catch (\Exception $e) {
            $dataPool = [];
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

        try {
            if ($year != 'all') {
                $differenceDataPool = $comparison->getComparison($year);
            }
        } catch (\Exception $e) {
            $differenceDataPool = [];
        }

        $data = [
            'current_year' => $year,
            'data_pool' => $dataPool,
            'featured_years' => $this->get('intracto_secret_santa.featured_years')->getFeaturedYears(),
            'google_data_pool' => $googleDataPool,
        ];

        if (isset($differenceDataPool)) {
            $data['difference_data_pool'] = $differenceDataPool;
        }

        $this->get('cache')->save('data' . $year, serialize($data), 86400);

        return $data;
    }
}