<?php

namespace Intracto\SecretSantaBundle\Query;

class ReportQuery
{
    /** @var PoolReportQuery */
    private $poolReportQuery;
    /** @var EntryReportQuery */
    private $entryReportQuery;
    /** @var IpReportQuery */
    private $ipReportQuery;
    /** @var WishlistReportQuery */
    private $wishlistReportQuery;
    /** @var FeaturedYearsQuery */
    private $featuredYearsQuery;

    /**
     * @param PoolReportQuery $poolReportQuery
     * @param EntryReportQuery $entryReportQuery
     * @param IpReportQuery $ipReportQuery
     * @param WishlistReportQuery $wishlistReportQuery
     * @param FeaturedYearsQuery $featuredYearsQuery
     */
    public function __construct(
        PoolReportQuery $poolReportQuery,
        EntryReportQuery $entryReportQuery,
        IpReportQuery $ipReportQuery,
        WishlistReportQuery $wishlistReportQuery,
        FeaturedYearsQuery $featuredYearsQuery
    ) {
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
        $season = new Season($year);

        $report = [
            'pools' => $this->poolReportQuery->countPools($season),
            'entries' => $this->entryReportQuery->countEntries($season),
            'confirmed_entries' => $this->entryReportQuery->countConfirmedEntries($season),
            'distinct_entries' => $this->entryReportQuery->countDistinctEntries($season),
            'entry_average' => $this->entryReportQuery->calculateAverageEntriesPerPool($season),
            'wishlist_average' => $this->wishlistReportQuery->calculateCompletedWishlists($season),
            'ip_usage' => $this->ipReportQuery->calculateIpUsage($season),
            'pool_chart_data' => $this->poolReportQuery->queryDataForMonthlyPoolChart($season),
            'entry_chart_data' => $this->entryReportQuery->queryDataForMonthlyEntryChart($season),
            'yearly_pool_chart_data' => $this->poolReportQuery->queryDataForYearlyPoolChart(),
            'yearly_entry_chart_data' => $this->entryReportQuery->queryDataForYearlyEntryChart(),
        ];

        if ($year) {
            $report['total_pools'] = $this->poolReportQuery->countAllPoolsUntilDate($season->getEnd());
            $report['total_entries'] = $this->entryReportQuery->countAllEntriesUntilDate($season->getEnd());
            $report['total_confirmed_entries'] = $this->entryReportQuery->countConfirmedEntriesUntilDate($season->getEnd());
            $report['total_entry_average'] = $this->entryReportQuery->calculateAverageEntriesPerPoolUntilDate($season->getEnd());
            $report['total_wishlist_average'] = $this->wishlistReportQuery->calculateCompletedWishlistsUntilDate($season->getEnd());
            $report['total_distinct_entries'] = $this->entryReportQuery->countDistinctEntriesUntilDate($season->getEnd());
            $report['total_pool_chart_data'] = $this->poolReportQuery->queryDataForPoolChartUntilDate($season->getEnd());
            $report['total_entry_chart_data'] = $this->entryReportQuery->queryDataForEntryChartUntilDate($season->getEnd());
        }

        return $report;
    }
}
