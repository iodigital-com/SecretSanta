<?php

namespace Intracto\SecretSantaBundle\Query;

class SeasonComparisonReportQuery
{
    /** @var PartyReportQuery */
    private $poolReportQuery;
    /** @var ParticipantReportQuery */
    private $participantReportQuery;
    /** @var WishlistReportQuery */
    private $wishlistReportQuery;

    /**
     * @param PartyReportQuery       $poolReportQuery
     * @param ParticipantReportQuery $participantReportQuery
     * @param WishlistReportQuery    $wishlistReportQuery
     */
    public function __construct(
        PartyReportQuery $poolReportQuery,
        ParticipantReportQuery $participantReportQuery,
        WishlistReportQuery $wishlistReportQuery
    ) {
        $this->poolReportQuery = $poolReportQuery;
        $this->participantReportQuery = $participantReportQuery;
        $this->wishlistReportQuery = $wishlistReportQuery;
    }

    /**
     * @param $year
     *
     * @return array
     */
    public function getComparison($year)
    {
        $season1 = new Season($year);
        $season2 = new Season($year - 1);

        return [
            'pool_count_difference' => $this->poolReportQuery->calculatePartyCountDifferenceBetweenSeasons($season1, $season2),
            'entry_count_difference' => $this->participantReportQuery->calculateParticipantCountDifferenceBetweenSeasons($season1, $season2),
            'confirmed_entry_count_difference' => $this->participantReportQuery->calculateConfirmedParticipantsCountDifferenceBetweenSeasons($season1, $season2),
            'distinct_entry_count_difference' => $this->participantReportQuery->calculateDistinctParticipantCountDifferenceBetweenSeasons($season1, $season2),
            'average_entries_difference' => $this->participantReportQuery->calculateAverageParticipantsPerPartyBetweenSeasons($season1, $season2),
            'average_wishlist_difference' => $this->wishlistReportQuery->calculateCompletedWishlistDifferenceBetweenSeasons($season1, $season2),
        ];
    }
}
