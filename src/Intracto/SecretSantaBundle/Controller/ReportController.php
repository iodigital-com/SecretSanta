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
                'party_data' => $cache['party_data'],
                'featured_years' => $cache['featured_years'],
                'google_data' => $cache['google_data'],
            ];

            if (isset($cache['difference_party_data'])) {
                $data['difference_party_data'] = $cache['difference_party_data'];
            }

            return $data;
        }

        try {
            if ($year != 'all') {
                $partyData = $reportQuery->getPartyReport($year);
            } else {
                $partyData = $reportQuery->getPartyReport();
            }
        } catch (\Exception $e) {
            $partyData = [];
        }

        try {
            if ($year != 'all') {
                $googlePartyData = $googleAnalyticsQuery->getAnalyticsReport($year);
            } else {
                $googlePartyData = $googleAnalyticsQuery->getAnalyticsReport();
            }
        } catch (\Exception $e) {
            $googlePartyData = [];
        }

        try {
            if ($year != 'all') {
                $differencePartyData = $seasonComparisonReportQuery->getComparison($year);
            }
        } catch (\Exception $e) {
            $differencePartyData = [];
        }

        $data = [
            'current_year' => $year,
            'party_data' => $partyData,
            'featured_years' => $featuredYearsQuery,
            'google_data' => $googlePartyData,
        ];

        if (isset($differencePartyData)) {
            $data['difference_party_data'] = $differencePartyData;
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
