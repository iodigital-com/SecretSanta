<?php

namespace Intracto\SecretSantaBundle\Query;

class ReportQuery
{
    /** @var PartyReportQuery */
    private $poolReportQuery;
    /** @var ParticipantReportQuery */
    private $participantReportQuery;
    /** @var IpReportQuery */
    private $ipReportQuery;
    /** @var WishlistReportQuery */
    private $wishlistReportQuery;

    /**
     * @param PartyReportQuery       $poolReportQuery
     * @param ParticipantReportQuery $participantReportQuery
     * @param IpReportQuery          $ipReportQuery
     * @param WishlistReportQuery    $wishlistReportQuery
     */
    public function __construct(
        PartyReportQuery $poolReportQuery,
        ParticipantReportQuery $participantReportQuery,
        IpReportQuery $ipReportQuery,
        WishlistReportQuery $wishlistReportQuery
    ) {
        $this->poolReportQuery = $poolReportQuery;
        $this->participantReportQuery = $participantReportQuery;
        $this->ipReportQuery = $ipReportQuery;
        $this->wishlistReportQuery = $wishlistReportQuery;
    }

    /**
     * @param null $year
     *
     * @return array
     */
    public function getPartyReport($year = null)
    {
        $season = new Season($year);

        $report = [
            'pools' => $this->poolReportQuery->countParties($season),
            'participants' => $this->participantReportQuery->countParticipants($season),
            'confirmed_entries' => $this->participantReportQuery->countConfirmedParticipants($season),
            'distinct_entries' => $this->participantReportQuery->countDistinctEntries($season),
            'entry_average' => $this->participantReportQuery->calculateAverageParticipantsPerParty($season),
            'wishlist_average' => $this->wishlistReportQuery->calculateCompletedWishlists($season),
            'ip_usage' => $this->ipReportQuery->calculateIpUsage($season),
            'pool_chart_data' => $this->poolReportQuery->queryDataForMonthlyPartyChart($season),
            'entry_chart_data' => $this->participantReportQuery->queryDataForMonthlyParticipantChart($season),
            'yearly_pool_chart_data' => $this->poolReportQuery->queryDataForYearlyPartyChart(),
            'yearly_entry_chart_data' => $this->participantReportQuery->queryDataForYearlyParticipantChart(),
        ];

        if ($year) {
            $report['total_pools'] = $this->poolReportQuery->countAllPartiesUntilDate($season->getEnd());
            $report['total_entries'] = $this->participantReportQuery->countAllParticipantsUntilDate($season->getEnd());
            $report['total_confirmed_entries'] = $this->participantReportQuery->countConfirmedParticipantsUntilDate($season->getEnd());
            $report['total_entry_average'] = $this->participantReportQuery->calculateAverageParticipantsPerPartyUntilDate($season->getEnd());
            $report['total_wishlist_average'] = $this->wishlistReportQuery->calculateCompletedWishlistsUntilDate($season->getEnd());
            $report['total_distinct_entries'] = $this->participantReportQuery->countDistinctParticipantsUntilDate($season->getEnd());
            $report['total_pool_chart_data'] = $this->poolReportQuery->queryDataForPartyChartUntilDate($season->getEnd());
            $report['total_entry_chart_data'] = $this->participantReportQuery->queryDataForParticipantChartUntilDate($season->getEnd());
        }

        return $report;
    }
}
