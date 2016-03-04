<?php

namespace Intracto\SecretSantaBundle\Query;

use Intracto\SecretSantaBundle\Query\Period;

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
            $firstDay = \DateTime::createFromFormat('Y-m-d', $year . '-04-01')->format('Y-m-d H:i:s');
            $lastDay = \DateTime::createFromFormat('Y-m-d', $year + 1 . '-04-01')->format('Y-m-d H:i:s');

            $period = new Period($lastDay, $firstDay);
            $totalUntil = new Period($lastDay);

            return [
                'pools' => $this->poolReportQuery->countPools($period),
                'total_pools' => $this->poolReportQuery->countAllPoolsUntilDate($totalUntil),
                'entries' => $this->entryReportQuery->countEntries($period),
                'total_entries' => $this->entryReportQuery->countAllEntriesUntilDate($totalUntil),
                'confirmed_entries' => $this->entryReportQuery->countConfirmedEntries($period),
                'total_confirmed_entries' => $this->entryReportQuery->countConfirmedEntries($period),
                'distinct_entries' => $this->entryReportQuery->countDistinctEntries($period),
                'total_distinct_entries' => $this->entryReportQuery->countDistinctEntries($period),
                'entry_average' => $this->entryReportQuery->calculateAverageEntriesPerPool($period),
                'total_entry_average' => $this->entryReportQuery->calculateAverageEntriesPerPoolUntilDate($totalUntil),
                'wishlist_average' => $this->wishlistReportQuery->calculateCompletedWishlists($period),
                'total_wishlist_average' => $this->wishlistReportQuery->calculateCompletedWishlistsUntilDate($totalUntil),
                'ip_usage' => $this->ipReportQuery->calculateIpUsage($period),
                'pool_chart_data' => $this->poolReportQuery->queryDataForPoolChart($period),
                'total_pool_chart_data' => $this->poolReportQuery->queryAllDataUntilDateForPoolChart($totalUntil),
                'entry_chart_data' => $this->entryReportQuery->queryDataForEntryChart($period),
                'total_entry_chart_data' => $this->entryReportQuery->queryAllDataForEntryChart($totalUntil),
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