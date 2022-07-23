<?php

namespace App\Query;

use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Component\Routing\RouterInterface;

class ParticipantReportQuery
{
    private Connection $dbal;
    private RouterInterface $router;
    private PartyReportQuery $partyReportQuery;
    private FeaturedYearsQuery $featuredYearsQuery;

    public function __construct(
        Connection $dbal,
        RouterInterface $router,
        PartyReportQuery $partyReportQuery,
        FeaturedYearsQuery $featuredYearsQuery
    ) {
        $this->dbal = $dbal;
        $this->router = $router;
        $this->partyReportQuery = $partyReportQuery;
        $this->featuredYearsQuery = $featuredYearsQuery;
    }

    /**
     * @return mixed
     */
    public function countConfirmedParticipantsUntilDate(\DateTime $date)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(p.id) AS confirmedParticipantCount')
            ->from('party', 'p')
            ->innerJoin('p', 'participant', 'e', 'p.id = e.party_id')
            ->where('p.sent_date < :lastDay')
            ->andWhere('e.view_date IS NOT NULL')
            ->setParameter('lastDay', $date->format('Y-m-d H:i:s'));

        return $query->execute()->fetchAll();
    }

    /**
     * @return mixed
     */
    public function countDistinctParticipantsUntilDate(\DateTime $date)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(distinct e.email) AS distinctParticipantCount')
            ->from('party', 'p')
            ->innerJoin('p', 'participant', 'e', 'p.id = e.party_id')
            ->where('p.sent_date < :lastDay')
            ->setParameter('lastDay', $date->format('Y-m-d H:i:s'));

        return $query->execute()->fetchAll();
    }

    /**
     * @return mixed
     */
    public function queryDataForMonthlyParticipantChart(Season $season)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(p.id) AS accumulatedParticipantCountByMonth, p.sent_date AS month')
            ->from('party', 'p')
            ->innerJoin('p', 'participant', 'e', 'p.id = e.party_id')
            ->where('p.sent_date >= :firstDay')
            ->andWhere('p.sent_date < :lastDay')
            ->groupBy('month(p.sent_date)')
            ->orderBy('month(p.sent_date) < 4, month(p.sent_date)')
            ->setParameter('firstDay', $season->getStart()->format('Y-m-d H:i:s'))
            ->setParameter('lastDay', $season->getEnd()->format('Y-m-d H:i:s'));

        return $query->execute()->fetchAll();
    }

    public function queryDataForYearlyParticipantChart(): array
    {
        $featuredYears = $this->featuredYearsQuery->getFeaturedYears();
        $participantChartData = [];

        foreach ($featuredYears['featured_years'] as $year) {
            $firstDay = \DateTime::createFromFormat('Y-m-d', $year.'-04-01')->format('Y-m-d H:i:s');
            $lastDay = \DateTime::createFromFormat('Y-m-d', $year + 1 .'-04-01')->format('Y-m-d H:i:s');

            $query = $this->dbal->createQueryBuilder()
                ->select('count(p.id) AS accumulatedParticipantCountByYear')
                ->from('party', 'p')
                ->innerJoin('p', 'participant', 'e', 'p.id = e.party_id')
                ->where('p.sent_date IS NOT NULL')
                ->andWhere('p.sent_date >= :firstDay')
                ->andWhere('p.sent_date < :lastDay')
                ->setParameter('firstDay', $firstDay)
                ->setParameter('lastDay', $lastDay);

            $chartData = $query->execute()->fetchAll();

            $participant = [
                'year' => $year,
                'participant' => $chartData,
            ];

            $participantChartData[] = $participant;
        }

        return $participantChartData;
    }

    public function queryDataForParticipantChartUntilDate(\DateTime $date): array
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(p.id) AS totalParticipantCount, p.sent_date AS month')
            ->from('party', 'p')
            ->innerJoin('p', 'participant', 'e', 'p.id = e.party_id')
            ->where('p.sent_date < :lastDay')
            ->groupBy('year(p.sent_date), month(p.sent_date)')
            ->setParameter('lastDay', $date->format('Y-m-d H:i:s'));

        $totalParticipantChartData = $query->execute()->fetchAll();

        $accumulatedParticipantCounter = 0;

        foreach ($totalParticipantChartData as &$participantCount) {
            $accumulatedParticipantCounter += $participantCount['totalParticipantCount'];
            $participantCount['totalParticipantCount'] = $accumulatedParticipantCounter;
        }

        return $totalParticipantChartData;
    }

    public function calculateAverageParticipantsPerPartyUntilDate(\DateTime $date): float
    {
        $totalParties = $this->partyReportQuery->countAllPartiesUntilDate($date);
        $totalParticipants = $this->countAllParticipantsUntilDate($date);

        if ($totalParties[0]['partyCount'] != 0) {
            return implode($totalParticipants[0]) / implode($totalParties[0]);
        }

        throw new NoResultException();
    }

    /**
     * @return mixed
     */
    public function countAllParticipantsUntilDate(\DateTime $date)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(p.id) AS totalParticipantCount')
            ->from('party', 'p')
            ->innerJoin('p', 'participant', 'e', 'p.id = e.party_id')
            ->where('p.sent_date < :lastDay')
            ->setParameter('lastDay', $date->format('Y-m-d H:i:s'));

        return $query->execute()->fetchAll();
    }

    /**
     * @return mixed
     */
    public function calculateParticipantCountDifferenceBetweenSeasons(Season $season1, Season $season2)
    {
        $participantCountSeason1 = $this->countParticipants($season1);

        try {
            $participantCountSeason2 = $this->countParticipants($season2);
        } catch (\Exception $e) {
            return $participantCountSeason1;
        }

        return $participantCountSeason1 - $participantCountSeason2;
    }

    public function countParticipants(Season $season): int
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(p.id) AS participant_count')
            ->from('party', 'p')
            ->innerJoin('p', 'participant', 'e', 'p.id = e.party_id')
            ->where('p.sent_date >= :firstDay')
            ->andWhere('p.sent_date < :lastDay')
            ->setParameter('firstDay', $season->getStart()->format('Y-m-d H:i:s'))
            ->setParameter('lastDay', $season->getEnd()->format('Y-m-d H:i:s'));

        $participantCount = $query->execute()->fetchAll();

        return (int) $participantCount[0]['participant_count'];
    }

    /**
     * @return mixed
     */
    public function calculateConfirmedParticipantsCountDifferenceBetweenSeasons(Season $season1, Season $season2)
    {
        $confirmedParticipantCountSeason1 = $this->countConfirmedParticipants($season1);

        try {
            $confirmedParticipantCountSeason2 = $this->countConfirmedParticipants($season2);
        } catch (\Exception $e) {
            return $confirmedParticipantCountSeason1[0]['confirmedParticipantCount'];
        }

        return $confirmedParticipantCountSeason1[0]['confirmedParticipantCount'] - $confirmedParticipantCountSeason2[0]['confirmedParticipantCount'];
    }

    /**
     * @return mixed
     */
    public function countConfirmedParticipants(Season $season)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(p.id) AS confirmedParticipantCount')
            ->from('party', 'p')
            ->innerJoin('p', 'participant', 'e', 'p.id = e.party_id')
            ->where('p.sent_date >= :firstDay')
            ->andWhere('p.sent_date < :lastDay')
            ->andWhere('e.view_date IS NOT NULL')
            ->setParameter('firstDay', $season->getStart()->format('Y-m-d H:i:s'))
            ->setParameter('lastDay', $season->getEnd()->format('Y-m-d H:i:s'));

        return $query->execute()->fetchAll();
    }

    /**
     * @return mixed
     */
    public function calculateDistinctParticipantCountDifferenceBetweenSeasons(Season $season1, Season $season2)
    {
        $distinctParticipantCountSeason1 = $this->countDistinctParticipants($season1);

        try {
            $distinctParticipantCountSeason2 = $this->countDistinctParticipants($season2);
        } catch (\Exception $e) {
            return $distinctParticipantCountSeason1[0]['distinctParticipantCount'];
        }

        return $distinctParticipantCountSeason1[0]['distinctParticipantCount'] - $distinctParticipantCountSeason2[0]['distinctParticipantCount'];
    }

    /**
     * @return mixed
     */
    public function countDistinctParticipants(Season $season)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(distinct e.email) AS distinctParticipantCount')
            ->from('party', 'p')
            ->innerJoin('p', 'participant', 'e', 'p.id = e.party_id')
            ->where('p.sent_date >= :firstDay')
            ->andWhere('p.sent_date < :lastDay')
            ->setParameter('firstDay', $season->getStart()->format('Y-m-d H:i:s'))
            ->setParameter('lastDay', $season->getEnd()->format('Y-m-d H:i:s'));

        return $query->execute()->fetchAll();
    }

    public function calculateAverageParticipantsPerPartyBetweenSeasons(Season $season1, Season $season2): float
    {
        $averageSeason1 = $this->calculateAverageParticipantsPerParty($season1);

        try {
            $averageSeason2 = $this->calculateAverageParticipantsPerParty($season2);
        } catch (\Exception $e) {
            return $averageSeason1;
        }

        return $averageSeason1 - $averageSeason2;
    }

    public function calculateAverageParticipantsPerParty(Season $season): float
    {
        $partyCount = $this->partyReportQuery->countParties($season);
        $participantCount = $this->countParticipants($season);

        if ($partyCount !== 0 && $participantCount !== 0) {
            return $participantCount / $partyCount;
        }

        throw new NoResultException();
    }

    /**
     * @return iterable
     */
    public function fetchMailsForExport(Season $season, bool $isAdmin)
    {
        /** @var QueryBuilder $subQuery */
        $subQuery = $this->dbal->createQueryBuilder();

        /* when there are duplicate emails, fetch only the one with the highest sent_date */
        $subQuery->select('max(p2.sent_date)')
            ->from('party', 'p2')
            ->join('p2', 'participant', 'e2', 'p2.id = e2.party_id')
            ->leftJoin('e2', 'blacklist_email', 'b2', 'b2.email = e2.email')
            ->where('p2.sent_date >= :firstDay')
            ->andWhere('p2.sent_date < :lastDay')
            ->andWhere('e2.party_admin = :admin')
            ->andWhere('e2.subscribed_for_updates = 1')
            ->andWhere('b2.id is null')
            ->andWhere('e2.email = e.email');

        /** @var QueryBuilder $qb */
        $qb = $this->dbal->createQueryBuilder();

        $qb->select('e.name, e.email, e.party_id, e.url, p.locale, p.list_url')
            ->from('party', 'p')
            ->join('p', 'participant', 'e', 'p.id = e.party_id')
            ->leftJoin('e', 'blacklist_email', 'b', 'b.email = e.email')
            ->where('p.sent_date >= :firstDay')
            ->andWhere('p.sent_date < :lastDay')
            ->andWhere('e.party_admin = :admin')
            ->andWhere('e.subscribed_for_updates = 1')
            ->andWhere('b.id is null')
            ->andWhere("p.sent_date = ({$subQuery->getSQL()})")
            ->groupBy('e.email') /*filter duplicates in same party */
            ->orderBy('p.id', Criteria::DESC)
            ->setParameter(':firstDay', $season->getStart()->format('Y-m-d H:i:s'))
            ->setParameter(':lastDay', $season->getEnd()->format('Y-m-d H:i:s'))
            ->setParameter(':admin', ($isAdmin ? 1 : 0));

        $result = $qb->execute()->fetchAll();

        return $result;
    }

    public function fetchDataForPartyUpdateMail(string $listUrl): array
    {
        $party = $this->dbal->createQueryBuilder()
            ->select('p.*, e.name AS adminName')
            ->from('party', 'p')
            ->innerJoin('p', 'participant', 'e', 'p.id = e.party_id')
            ->where('p.list_url = :listurl')
            ->andWhere('e.party_admin = 1')
            ->setParameter(':listurl', $listUrl);
        $participantCount = $this->dbal->createQueryBuilder()
            ->select('count(e.id) AS participantCount')
            ->from('party', 'p')
            ->innerJoin('p', 'participant', 'e', 'p.id = e.party_id')
            ->where('p.list_url = :listurl')
            ->setParameter(':listurl', $listUrl);
        $wishlistCount = $this->dbal->createQueryBuilder()
            ->select('count(e.id) AS wishlistCount')
            ->from('party', 'p')
            ->innerJoin('p', 'participant', 'e', 'p.id = e.party_id')
            ->where('p.list_url = :listurl')
            ->andWhere('wishlist_updated_time IS NOT NULL')
            ->setParameter(':listurl', $listUrl);
        $viewedCount = $this->dbal->createQueryBuilder()
            ->select('count(e.id) AS viewedCount')
            ->from('party', 'p')
            ->innerJoin('p', 'participant', 'e', 'p.id = e.party_id')
            ->where('p.list_url = :listurl')
            ->andWhere('view_date is not null')
            ->setParameter(':listurl', $listUrl);

        return [
            'party' => $party->execute()->fetchAll(),
            'participantCount' => $participantCount->execute()->fetchAll(),
            'wishlistCount' => $wishlistCount->execute()->fetchAll(),
            'viewedCount' => $viewedCount->execute()->fetchAll(),
        ];
    }

    /**
     * @return mixed
     */
    public function findBuddyByParticipantId(int $participantId)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('p.id')
            ->from('participant', 'p')
            ->where('p.assigned_participant_id = :id')
            ->setParameter('id', $participantId);

        return $query->execute()->fetchAll();
    }
}
