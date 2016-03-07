<?php

namespace Intracto\SecretSantaBundle\Query;

class ReportQuery
{
    private $poolReportQuery;
    private $entryReportQuery;
    private $ipReportQuery;
    private $wishlistReportQuery;
    private $featuredYearsQuery;

    /**
     * @param $poolReportQuery
     * @param $entryReportQuery
     * @param $ipReportQuery
     * @param $wishlistReportQuery
     * @param $featuredYearsQuery
     */
    public function __construct($poolReportQuery, $entryReportQuery, $ipReportQuery, $wishlistReportQuery, $featuredYearsQuery)
    {
        $this->poolReportQuery = $poolReportQuery;
        $this->entryReportQuery = $entryReportQuery;
        $this->ipReportQuery = $ipReportQuery;
        $this->wishlistReportQuery = $wishlistReportQuery;
        $this->featuredYearsQuery = $featuredYearsQuery;
    }

    /**
     * @param null $year
     * @return array
     */
    public function getPoolReport($year = null)
    {
        $poolYear = new PoolYear($year);

        $report = [
            'pools' => $this->poolReportQuery->countPools($poolYear),
            'entries' => $this->entryReportQuery->countEntries($poolYear),
            'confirmed_entries' => $this->entryReportQuery->countConfirmedEntries($poolYear),
            'distinct_entries' => $this->entryReportQuery->countDistinctEntries($poolYear),
            'entry_average' => $this->entryReportQuery->calculateAverageEntriesPerPool($poolYear),
            'wishlist_average' => $this->wishlistReportQuery->calculateCompletedWishlists($poolYear),
            'ip_usage' => $this->ipReportQuery->calculateIpUsage($poolYear),
            'pool_chart_data' => $this->poolReportQuery->queryDataForMonthlyPoolChart($poolYear),
            'entry_chart_data' => $this->entryReportQuery->queryDataForMonthlyEntryChart($poolYear),
            'yearly_pool_chart_data' => $this->poolReportQuery->queryDataForYearlyPoolChart(),
            'yearly_entry_chart_data' => $this->entryReportQuery->queryDataForYearlyEntryChart(),
        ];

        if ($year) {
            $report['total_pools'] = $this->poolReportQuery->countAllPoolsUntilDate($poolYear->getEnd());
            $report['total_entries'] = $this->entryReportQuery->countAllEntriesUntilDate($poolYear->getEnd());
            $report['total_confirmed_entries'] = $this->entryReportQuery->countConfirmedEntriesUntilDate($poolYear->getEnd());
            $report['total_entry_average'] = $this->entryReportQuery->calculateAverageEntriesPerPoolUntilDate($poolYear->getEnd());
            $report['total_wishlist_average'] = $this->wishlistReportQuery->calculateCompletedWishlistsUntilDate($poolYear->getEnd());
            $report['total_distinct_entries'] = $this->entryReportQuery->countDistinctEntriesUntilDate($poolYear->getEnd());
            $report['total_pool_chart_data'] = $this->poolReportQuery->queryDataForPoolChartUntilDate($poolYear->getEnd());
            $report['total_entry_chart_data'] = $this->entryReportQuery->queryDataForEntryChartUntilDate($poolYear->getEnd());
        }

        return $report;
    }
}