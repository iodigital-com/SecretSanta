<?php

namespace Intracto\SecretSantaBundle\Service;

use Intracto\SecretSantaBundle\Query\FeaturedYearsQuery;
use Intracto\SecretSantaBundle\Query\GoogleAnalyticsQuery;
use Intracto\SecretSantaBundle\Query\ReportQuery;
use Intracto\SecretSantaBundle\Query\SeasonComparisonReportQuery;

class ReportQueriesService
{
    private GoogleAnalyticsQuery $googleAnalyticsQuery;
    private ReportQuery $reportQuery;
    private SeasonComparisonReportQuery $seasonComparisonReportQuery;
    private FeaturedYearsQuery $featuredYearsQuery;

    public function __construct(
        GoogleAnalyticsQuery $googleAnalyticsQuery,
        ReportQuery $reportQuery,
        SeasonComparisonReportQuery $seasonComparisonReportQuery,
        FeaturedYearsQuery $featuredYearsQuery
    ) {
        $this->googleAnalyticsQuery = $googleAnalyticsQuery;
        $this->reportQuery = $reportQuery;
        $this->seasonComparisonReportQuery = $seasonComparisonReportQuery;
        $this->featuredYearsQuery = $featuredYearsQuery;
    }

    public function getReportResults(string $year): array
    {
        try {
            if ('all' !== $year) {
                $partyData = $this->reportQuery->getPartyReport($year);
            } else {
                $partyData = $this->reportQuery->getPartyReport();
            }
        } catch (\Exception $e) {
            $partyData = [];
        }

        try {
            if ('all' !== $year) {
                $googlePartyData = $this->googleAnalyticsQuery->getAnalyticsReport((int) $year);
            } else {
                $googlePartyData = $this->googleAnalyticsQuery->getAnalyticsReport();
            }
        } catch (\Exception $e) {
            $googlePartyData = [];
        }

        $differencePartyData = [];

        try {
            if ('all' !== $year) {
                $differencePartyData = $this->seasonComparisonReportQuery->getComparison($year);
            }
        } catch (\Exception $e) {
            $differencePartyData = [];
        }

        $data = [
            'current_year' => $year,
            'party_data' => $partyData,
            'featured_years' => $this->featuredYearsQuery->getFeaturedYears(),
            'google_data' => $googlePartyData,
        ];

        if (!empty($differencePartyData)) {
            $data['difference_party_data'] = $differencePartyData;
        }

        return $data;
    }
}
