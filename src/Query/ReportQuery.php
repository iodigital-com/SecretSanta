<?php

namespace App\Query;

class ReportQuery
{
    private PartyReportQuery $partyReportQuery;
    private ParticipantReportQuery $participantReportQuery;
    private IpReportQuery $ipReportQuery;
    private WishlistReportQuery $wishlistReportQuery;

    public function __construct(
        PartyReportQuery $partyReportQuery,
        ParticipantReportQuery $participantReportQuery,
        IpReportQuery $ipReportQuery,
        WishlistReportQuery $wishlistReportQuery
    ) {
        $this->partyReportQuery = $partyReportQuery;
        $this->participantReportQuery = $participantReportQuery;
        $this->ipReportQuery = $ipReportQuery;
        $this->wishlistReportQuery = $wishlistReportQuery;
    }

    public function getPartyReport(?int $year = null): array
    {
        $season = new Season($year);

        $report = [
            'parties' => $this->partyReportQuery->countParties($season),
            'participants' => $this->participantReportQuery->countParticipants($season),
            'confirmed_participants' => $this->participantReportQuery->countConfirmedParticipants($season),
            'distinct_participants' => $this->participantReportQuery->countDistinctParticipants($season),
            'participant_average' => $this->participantReportQuery->calculateAverageParticipantsPerParty($season),
            'wishlist_average' => $this->wishlistReportQuery->calculateCompletedWishlists($season),
            'ip_usage' => $this->ipReportQuery->calculateIpUsage($season),
            'party_chart_data' => $this->partyReportQuery->queryDataForMonthlyPartyChart($season),
            'participant_chart_data' => $this->participantReportQuery->queryDataForMonthlyParticipantChart($season),
            'yearly_party_chart_data' => $this->partyReportQuery->queryDataForYearlyPartyChart(),
            'yearly_participant_chart_data' => $this->participantReportQuery->queryDataForYearlyParticipantChart(),
        ];

        if ($year) {
            $report['total_parties'] = $this->partyReportQuery->countAllPartiesUntilDate($season->getEnd());
            $report['total_participants'] = $this->participantReportQuery->countAllParticipantsUntilDate($season->getEnd());
            $report['total_confirmed_participants'] = $this->participantReportQuery->countConfirmedParticipantsUntilDate($season->getEnd());
            $report['total_participant_average'] = $this->participantReportQuery->calculateAverageParticipantsPerPartyUntilDate($season->getEnd());
            $report['total_wishlist_average'] = $this->wishlistReportQuery->calculateCompletedWishlistsUntilDate($season->getEnd());
            $report['total_distinct_participants'] = $this->participantReportQuery->countDistinctParticipantsUntilDate($season->getEnd());
            $report['total_party_chart_data'] = $this->partyReportQuery->queryDataForPartyChartUntilDate($season->getEnd());
            $report['total_participant_chart_data'] = $this->participantReportQuery->queryDataForParticipantChartUntilDate($season->getEnd());
        }

        return $report;
    }
}
