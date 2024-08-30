<?php

namespace App\Service;

use App\Query\FeaturedYearsQuery;
use App\Query\GoogleAnalyticsQuery;
use App\Query\ReportQuery;
use App\Query\SeasonComparisonReportQuery;

class ReportQueriesService
{
    public function __construct(
        private GoogleAnalyticsQuery $googleAnalyticsQuery,
        private ReportQuery $reportQuery,
        private SeasonComparisonReportQuery $seasonComparisonReportQuery,
        private FeaturedYearsQuery $featuredYearsQuery
    ) {}

    public function getReportResults(string $year): array
    {
        try {
            if ('all' !== $year) {
                $partyData = $this->reportQuery->getPartyReport((int) $year);
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
                $differencePartyData = $this->seasonComparisonReportQuery->getComparison((int) $year);
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
