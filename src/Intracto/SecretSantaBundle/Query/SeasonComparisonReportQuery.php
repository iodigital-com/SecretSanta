<?php

namespace Intracto\SecretSantaBundle\Query;

class SeasonComparisonReportQuery
{
    /** @var PoolReportQuery */
    private $poolReportQuery;
    /** @var EntryReportQuery */
    private $entryReportQuery;
    /** @var WishlistReportQuery */
    private $wishlistReportQuery;

    /**
     * @param PoolReportQuery $poolReportQuery
     * @param EntryReportQuery $entryReportQuery
     * @param WishlistReportQuery $wishlistReportQuery
     */
    public function __construct(
        PoolReportQuery $poolReportQuery,
        EntryReportQuery $entryReportQuery,
        WishlistReportQuery $wishlistReportQuery
    ) {
        $this->poolReportQuery = $poolReportQuery;
        $this->entryReportQuery = $entryReportQuery;
        $this->wishlistReportQuery = $wishlistReportQuery;
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