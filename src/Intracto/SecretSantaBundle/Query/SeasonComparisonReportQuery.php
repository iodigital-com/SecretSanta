<?php

namespace Intracto\SecretSantaBundle\Query;

class SeasonComparisonReportQuery
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
     * @param $year
     * @return array
     */
    public function getComparison($year)
    {
        $season1 = new Season($year);
        $season2 = new Season($year - 1);

        return [
            'pool_count_difference' => $this->poolReportQuery->calculatePoolCountDifferenceBetweenSeasons($season1, $season2),
            'entry_count_difference' => $this->entryReportQuery->calculateEntryCountDifferenceBetweenSeasons($season1, $season2),
            'confirmed_entry_count_difference' => $this->entryReportQuery->calculateConfirmedEntryCountDifferenceBetweenSeasons($season1, $season2),
            'distinct_entry_count_difference' => $this->entryReportQuery->calculateDistinctEntryCountDifferenceBetweenSeasons($season1, $season2),
            'average_entries_difference' => $this->entryReportQuery->calculateAverageEntriesPerPoolBetweenSeasons($season1, $season2),
            'average_wishlist_difference' => $this->wishlistReportQuery->calculateCompletedWishlistDifferenceBetweenSeasons($season1, $season2),
        ];
    }
}