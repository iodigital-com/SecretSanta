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
    public function indexAction($year)
    {
        /** @var \Intracto\SecretSantaBundle\Query\GoogleAnalyticsQuery $googleAnalyticsQuery */
        $googleAnalyticsQuery = $this->get('intracto_secret_santa.query.google_analytics');
        /** @var \Intracto\SecretSantaBundle\Query\ReportQuery $reportQuery */
        $reportQuery = $this->get('intracto_secret_santa.query.report');
        /** @var \Intracto\SecretSantaBundle\Query\SeasonComparisonReportQuery $seasonComparisonReportQuery */
        $seasonComparisonReportQuery = $this->get('intracto_secret_santa.query.season_comparison_report');
        /** @var \Intracto\SecretSantaBundle\Query\FeaturedYearsQuery $featuredYearsQuery */
        $featuredYearsQuery = $this->get('intracto_secret_santa.query.featured_years_report')->getFeaturedYears();

        if ($reportQueryResult = $this->get('cache')->fetch('data'.$year)) {
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
                $dataPool = $reportQuery->getPartyReport($year);
            } else {
                $dataPool = $reportQuery->getPartyReport();
            }
        } catch (\Exception $e) {
            $dataPool = [];
        }

        try {
            if ($year != 'all') {
                $googleDataPool = $googleAnalyticsQuery->getAnalyticsReport($year);
            } else {
                $googleDataPool = $googleAnalyticsQuery->getAnalyticsReport();
            }
        } catch (\Exception $e) {
            $googleDataPool = [];
        }

        try {
            if ($year != 'all') {
                $differenceDataPool = $seasonComparisonReportQuery->getComparison($year);
            }
        } catch (\Exception $e) {
            $differenceDataPool = [];
        }

        $data = [
            'current_year' => $year,
            'data_pool' => $dataPool,
            'featured_years' => $featuredYearsQuery,
            'google_data_pool' => $googleDataPool,
        ];

        if (isset($differenceDataPool)) {
            $data['difference_data_pool'] = $differenceDataPool;
        }

        end($featuredYearsQuery['featured_years']);
        $lastKey = key($featuredYearsQuery['featured_years']);

        if ($year == 'all' || $year == $featuredYearsQuery['featured_years'][$lastKey]) {
            $this->get('cache')->save('data'.$year, serialize($data), 24 * 60 * 60);

            return $data;
        }

        $this->get('cache')->save('data'.$year, serialize($data));

        return $data;
    }
}
