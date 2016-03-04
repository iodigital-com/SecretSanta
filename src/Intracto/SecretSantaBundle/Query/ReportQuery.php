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
     * @param int $year
     * @return array
     */
    public function getPoolReport($year = null)
    {
        if ($year != null) {
            $firstDay = \DateTime::createFromFormat('Y-m-d', $year . '-04-01');
            $lastDay = \DateTime::createFromFormat('Y-m-d', $year + 1 . '-04-01');

            return [
                'pools' => $this->poolReportQuery->countPools($firstDay, $lastDay),
                'total_pools' => $this->poolReportQuery->countAllPoolsUntilDate($lastDay),
                'entries' => $this->entryReportQuery->countEntries($firstDay, $lastDay),
                'total_entries' => $this->entryReportQuery->countAllEntriesUntilDate($lastDay),
                'confirmed_entries' => $this->entryReportQuery->countConfirmedEntries($firstDay, $lastDay),
                'total_confirmed_entries' => $this->entryReportQuery->countConfirmedEntries($lastDay),
                'distinct_entries' => $this->entryReportQuery->countDistinctEntries($firstDay, $lastDay),
                'total_distinct_entries' => $this->entryReportQuery->countDistinctEntries($lastDay),
                'entry_average' => $this->entryReportQuery->calculateAverageEntriesPerPool($firstDay, $lastDay),
                'total_entry_average' => $this->entryReportQuery->calculateAverageEntriesPerPoolUntilDate($lastDay),
                'wishlist_average' => $this->wishlistReportQuery->calculateCompletedWishlists($firstDay, $lastDay),
                'total_wishlist_average' => $this->wishlistReportQuery->calculateCompletedWishlistsUntilDate($lastDay),
                'ip_usage' => $this->ipReportQuery->calculateIpUsage($firstDay, $lastDay),
                'pool_chart_data' => $this->poolReportQuery->queryDataForPoolChart($firstDay, $lastDay),
                'total_pool_chart_data' => $this->poolReportQuery->queryAllDataUntilDateForPoolChart($lastDay),
                'entry_chart_data' => $this->entryReportQuery->queryDataForEntryChart($firstDay, $lastDay),
                'total_entry_chart_data' => $this->entryReportQuery->queryAllDataForEntryChart($lastDay),
            ];
        }

        return [
            'pools' => $this->poolReportQuery->countPools(),
            'confirmed_pools' => $this->poolReportQuery->countConfirmedPools(),
            'entries' => $this->entryReportQuery->countEntries(),
            'distinct_entries' => $this->entryReportQuery->countDistinctEntries(),
            'confirmed_entries' => $this->entryReportQuery->countConfirmedEntries(),
            'entry_average' => $this->entryReportQuery->calculateAverageEntriesPerPool(),
            'wishlist_average' => $this->wishlistReportQuery->calculateCompletedWishlists(),
            'ip_usage' => $this->ipReportQuery->calculateIpUsage(),
            'full_pool_chart_data' => $this->poolReportQuery->queryDataForPoolChart(),
            'full_entry_chart_data' => $this->entryReportQuery->queryDataForEntryChart(),
        ];
    }
}