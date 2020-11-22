<?php

namespace App\Query;

class SeasonComparisonReportQuery
{
    private PartyReportQuery $partyReportQuery;
    private ParticipantReportQuery $participantReportQuery;
    private WishlistReportQuery $wishlistReportQuery;

    public function __construct(
        PartyReportQuery $partyReportQuery,
        ParticipantReportQuery $participantReportQuery,
        WishlistReportQuery $wishlistReportQuery
    ) {
        $this->partyReportQuery = $partyReportQuery;
        $this->participantReportQuery = $participantReportQuery;
        $this->wishlistReportQuery = $wishlistReportQuery;
    }

    public function getComparison(int $year): array
    {
        $season1 = new Season($year);
        $season2 = new Season($year - 1);

        return [
            'party_count_difference' => $this->partyReportQuery->calculatePartyCountDifferenceBetweenSeasons($season1, $season2),
            'participant_count_difference' => $this->participantReportQuery->calculateParticipantCountDifferenceBetweenSeasons($season1, $season2),
            'confirmed_participant_count_difference' => $this->participantReportQuery->calculateConfirmedParticipantsCountDifferenceBetweenSeasons($season1, $season2),
            'distinct_participant_count_difference' => $this->participantReportQuery->calculateDistinctParticipantCountDifferenceBetweenSeasons($season1, $season2),
            'average_participants_difference' => $this->participantReportQuery->calculateAverageParticipantsPerPartyBetweenSeasons($season1, $season2),
            'average_wishlist_difference' => $this->wishlistReportQuery->calculateCompletedWishlistDifferenceBetweenSeasons($season1, $season2),
        ];
    }
}
