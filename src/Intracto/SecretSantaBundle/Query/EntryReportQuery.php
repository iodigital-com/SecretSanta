<?php

namespace Intracto\SecretSantaBundle\Query;

use Doctrine\DBAL\Connection;
use Symfony\Component\Routing\Router;

class EntryReportQuery
{
    /** @var Connection */
    private $dbal;
    /** @var Router */
    private $router;
    /** @var PoolReportQuery */
    private $poolReportQuery;
    /** @var FeaturedYearsQuery */
    private $featuredYearsQuery;

    /**
     * @param Connection         $dbal
     * @param Router             $router
     * @param PoolReportQuery    $poolReportQuery
     * @param FeaturedYearsQuery $featuredYearsQuery
     */
    public function __construct(
        Connection $dbal,
        Router $router,
        PoolReportQuery $poolReportQuery,
        FeaturedYearsQuery $featuredYearsQuery
    ) {
        $this->dbal = $dbal;
        $this->router = $router;
        $this->poolReportQuery = $poolReportQuery;
        $this->featuredYearsQuery = $featuredYearsQuery;
    }

    /**
     * @param \DateTime $date
     *
     * @return mixed
     */
    public function countConfirmedEntriesUntilDate(\DateTime $date)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(p.id) AS confirmedEntryCount')
            ->from('Pool', 'p')
            ->innerJoin('p', 'Entry', 'e', 'p.id = e.poolId')
            ->where('p.sentdate < :lastDay')
            ->andWhere('e.viewdate IS NOT NULL')
            ->setParameter('lastDay', $date->format('Y-m-d H:i:s'));

        return $query->execute()->fetchAll();
    }

    /**
     * @param \DateTime $date
     *
     * @return mixed
     */
    public function countDistinctEntriesUntilDate(\DateTime $date)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(distinct e.email) AS distinctEntryCount')
            ->from('Pool', 'p')
            ->innerJoin('p', 'Entry', 'e', 'p.id = e.poolId')
            ->where('p.sentdate < :lastDay')
            ->setParameter('lastDay', $date->format('Y-m-d H:i:s'));

        return $query->execute()->fetchAll();
    }

    /**
     * @param Season $season
     *
     * @return mixed
     */
    public function queryDataForMonthlyEntryChart(Season $season)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(p.id) AS accumulatedEntryCountByMonth, p.sentdate AS month')
            ->from('Pool', 'p')
            ->innerJoin('p', 'Entry', 'e', 'p.id = e.poolId')
            ->where('p.sentdate >= :firstDay')
            ->andWhere('p.sentdate < :lastDay')
            ->groupBy('month(p.sentdate)')
            ->orderBy('month(p.sentdate) < 4, month(p.sentdate)')
            ->setParameter('firstDay', $season->getStart()->format('Y-m-d H:i:s'))
            ->setParameter('lastDay', $season->getEnd()->format('Y-m-d H:i:s'));

        return $query->execute()->fetchAll();
    }

    /**
     * @return array
     */
    public function queryDataForYearlyEntryChart()
    {
        $featuredYears = $this->featuredYearsQuery->getFeaturedYears();
        $entryChartData = [];

        foreach ($featuredYears['featured_years'] as $year) {
            $firstDay = \DateTime::createFromFormat('Y-m-d', $year.'-04-01')->format('Y-m-d H:i:s');
            $lastDay = \DateTime::createFromFormat('Y-m-d', $year + 1 .'-04-01')->format('Y-m-d H:i:s');

            $query = $this->dbal->createQueryBuilder()
                ->select('count(p.id) AS accumulatedEntryCountByYear')
                ->from('Pool', 'p')
                ->innerJoin('p', 'Entry', 'e', 'p.id = e.poolId')
                ->where('p.sentdate IS NOT NULL')
                ->andWhere('p.sentdate >= :firstDay')
                ->andWhere('p.sentdate < :lastDay')
                ->setParameter('firstDay', $firstDay)
                ->setParameter('lastDay', $lastDay);

            $chartData = $query->execute()->fetchAll();

            $entry = [
                'year' => $year,
                'entry' => $chartData,
            ];

            array_push($entryChartData, $entry);
        }

        return $entryChartData;
    }

    /**
     * @param \DateTime $date
     *
     * @return array
     */
    public function queryDataForEntryChartUntilDate(\DateTime $date)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(p.id) AS totalEntryCount, p.sentdate AS month')
            ->from('Pool', 'p')
            ->innerJoin('p', 'Entry', 'e', 'p.id = e.poolId')
            ->where('p.sentdate < :lastDay')
            ->groupBy('year(p.sentdate), month(p.sentdate)')
            ->setParameter('lastDay', $date->format('Y-m-d H:i:s'));

        $totalEntryChartData = $query->execute()->fetchAll();

        $accumulatedEntryCounter = 0;

        foreach ($totalEntryChartData as &$entryCount) {
            $accumulatedEntryCounter += $entryCount['totalEntryCount'];
            $entryCount['totalEntryCount'] = $accumulatedEntryCounter;
        }

        return $totalEntryChartData;
    }

    /**
     * @param \DateTime $date
     *
     * @return float
     */
    public function calculateAverageEntriesPerPoolUntilDate(\DateTime $date)
    {
        $totalPools = $this->poolReportQuery->countAllPoolsUntilDate($date);
        $totalEntries = $this->countAllEntriesUntilDate($date);

        if ($totalPools[0]['poolCount'] != 0) {
            return implode($totalEntries[0]) / implode($totalPools[0]);
        }

        throw new NoResultException();
    }

    /**
     * @param \DateTime $date
     *
     * @return mixed
     */
    public function countAllEntriesUntilDate(\DateTime $date)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(p.id) AS totalEntryCount')
            ->from('Pool', 'p')
            ->innerJoin('p', 'Entry', 'e', 'p.id = e.poolId')
            ->where('p.sentdate < :lastDay')
            ->setParameter('lastDay', $date->format('Y-m-d H:i:s'));

        return $query->execute()->fetchAll();
    }

    /**
     * @param Season $season1
     * @param Season $season2
     *
     * @return mixed
     */
    public function calculateEntryCountDifferenceBetweenSeasons(Season $season1, Season $season2)
    {
        $entryCountSeason1 = $this->countEntries($season1);
        try {
            $entryCountSeason2 = $this->countEntries($season2);
        } catch (\Exception $e) {
            return $entryCountSeason1[0]['entryCount'];
        }

        return $entryCountSeason1[0]['entryCount'] - $entryCountSeason2[0]['entryCount'];
    }

    /**
     * @param Season $season
     *
     * @return mixed
     */
    public function countEntries(Season $season)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(p.id) AS entryCount')
            ->from('Pool', 'p')
            ->innerJoin('p', 'Entry', 'e', 'p.id = e.poolId')
            ->where('p.sentdate >= :firstDay')
            ->andWhere('p.sentdate < :lastDay')
            ->setParameter('firstDay', $season->getStart()->format('Y-m-d H:i:s'))
            ->setParameter('lastDay', $season->getEnd()->format('Y-m-d H:i:s'));

        return $query->execute()->fetchAll();
    }

    /**
     * @param Season $season1
     * @param Season $season2
     *
     * @return mixed
     */
    public function calculateConfirmedEntryCountDifferenceBetweenSeasons(Season $season1, Season $season2)
    {
        $confirmedEntryCountSeason1 = $this->countConfirmedEntries($season1);
        try {
            $confirmedEntryCountSeason2 = $this->countConfirmedEntries($season2);
        } catch (\Exception $e) {
            return $confirmedEntryCountSeason1[0]['confirmedEntryCount'];
        }

        return $confirmedEntryCountSeason1[0]['confirmedEntryCount'] - $confirmedEntryCountSeason2[0]['confirmedEntryCount'];
    }

    /**
     * @param Season $season
     *
     * @return mixed
     */
    public function countConfirmedEntries(Season $season)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(p.id) AS confirmedEntryCount')
            ->from('Pool', 'p')
            ->innerJoin('p', 'Entry', 'e', 'p.id = e.poolId')
            ->where('p.sentdate >= :firstDay')
            ->andWhere('p.sentdate < :lastDay')
            ->andWhere('e.viewdate IS NOT NULL')
            ->setParameter('firstDay', $season->getStart()->format('Y-m-d H:i:s'))
            ->setParameter('lastDay', $season->getEnd()->format('Y-m-d H:i:s'));

        return $query->execute()->fetchAll();
    }

    /**
     * @param Season $season1
     * @param Season $season2
     *
     * @return mixed
     */
    public function calculateDistinctEntryCountDifferenceBetweenSeasons(Season $season1, Season $season2)
    {
        $distinctEntryCountSeason1 = $this->countDistinctEntries($season1);
        try {
            $distinctEntryCountSeason2 = $this->countDistinctEntries($season2);
        } catch (\Exception $e) {
            return $distinctEntryCountSeason1[0]['distinctEntryCount'];
        }

        return $distinctEntryCountSeason1[0]['distinctEntryCount'] - $distinctEntryCountSeason2[0]['distinctEntryCount'];
    }

    /**
     * @param Season $season
     *
     * @return mixed
     */
    public function countDistinctEntries(Season $season)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(distinct e.email) AS distinctEntryCount')
            ->from('Pool', 'p')
            ->innerJoin('p', 'Entry', 'e', 'p.id = e.poolId')
            ->where('p.sentdate >= :firstDay')
            ->andWhere('p.sentdate < :lastDay')
            ->setParameter('firstDay', $season->getStart()->format('Y-m-d H:i:s'))
            ->setParameter('lastDay', $season->getEnd()->format('Y-m-d H:i:s'));

        return $query->execute()->fetchAll();
    }

    /**
     * @param Season $season1
     * @param Season $season2
     *
     * @return float
     */
    public function calculateAverageEntriesPerPoolBetweenSeasons(Season $season1, Season $season2)
    {
        $averageSeason1 = $this->calculateAverageEntriesPerPool($season1);
        try {
            $averageSeason2 = $this->calculateAverageEntriesPerPool($season2);
        } catch (\Exception $e) {
            return $averageSeason1;
        }

        return $averageSeason1 - $averageSeason2;
    }

    /**
     * @param Season $season
     *
     * @return float
     */
    public function calculateAverageEntriesPerPool(Season $season)
    {
        $pools = $this->poolReportQuery->countPools($season);
        $entries = $this->countEntries($season);

        if ($pools[0]['poolCount'] != 0 || $entries[0]['entryCount'] != 0) {
            return implode($entries[0]) / implode($pools[0]);
        }

        throw new NoResultException();
    }

    /**
     * @param Season $season
     *
     * @return mixed
     */
    public function fetchAdminEmailsForExport(Season $season)
    {
        $reusePartyBaseUrl = $this->getPoolReuseBaseUrl();
        $handle = fopen('/tmp/'.date('Y-m-d-H.i.s').'_admins.csv', 'w+');

        $stmt = $this->dbal->executeQuery('
            SELECT e.name, e.email, e.party_id, p.locale, p.list_url
            FROM party p
            JOIN participant e ON p.id = e.party_id
            WHERE p.sent_date >= :firstDay
            AND p.sent_date < :lastDay
            AND e.party_admin = 1
            GROUP BY e.name, e.email, e.party_id',
            [
                'firstDay' => $season->getStart()->format('Y-m-d H:i:s'),
                'lastDay' => $season->getEnd()->format('Y-m-d H:i:s'),
            ]
        );

        while ($row = $stmt->fetch()) {
            fputcsv(
                $handle,
                [
                    $row['name'],
                    $row['email'],
                    $row['party_id'],
                    $row['locale'],
                    $reusePartyBaseUrl.$row['list_url'],
                ],
                ','
            );
        }

        fclose($handle);
    }

    private function getPoolReuseBaseUrl()
    {
        $url = $this->router->generate(
            'party_reuse',
            ['listUrl' => '1'],
            true
        );

        // URL was generated for party 1, strip the 1 to get the base URL
        return substr($url, 0, -1);
    }

    /**
     * @param Season $season
     *
     * @return mixed
     */
    public function fetchParticipantEmailsForExport(Season $season)
    {
        $handle = fopen('/tmp/'.date('Y-m-d-H.i.s').'_participants.csv', 'w+');

        $stmt = $this->dbal->executeQuery('
            SELECT e.name, e.email, e.party_id, p.locale
            FROM party p
            JOIN participant e ON p.id = e.party_id
            WHERE p.sent_date >= :firstDay
            AND p.sent_date < :lastDay
            AND e.party_admin = 0
            GROUP BY e.name, e.email, e.party_id',
            [
                'firstDay' => $season->getStart()->format('Y-m-d H:i:s'),
                'lastDay' => $season->getEnd()->format('Y-m-d H:i:s'),
            ]
        );

        while ($row = $stmt->fetch()) {
            fputcsv(
                $handle,
                [
                    $row['name'],
                    $row['email'],
                    $row['party_id'],
                    $row['locale'],
                ],
                ','
            );
        }

        fclose($handle);
    }

    /**
     * @param $listUrl
     *
     * @return array
     */
    public function fetchDataForPoolUpdateMail($listUrl)
    {
        $pool = $this->dbal->createQueryBuilder()
            ->select('p.*, e.name AS adminName')
            ->from('Pool', 'p')
            ->innerJoin('p', 'Entry', 'e', 'p.id = e.poolId')
            ->where('p.listurl = :listurl')
            ->andWhere('e.poolAdmin = 1')
            ->setParameter(':listurl', $listUrl);
        $participantCount = $this->dbal->createQueryBuilder()
            ->select('count(e.id) AS participantCount')
            ->from('Pool', 'p')
            ->innerJoin('p', 'Entry', 'e', 'p.id = e.poolId')
            ->where('p.listurl = :listurl')
            ->setParameter(':listurl', $listUrl);
        $wishlistCount = $this->dbal->createQueryBuilder()
            ->select('count(e.id) AS wishlistCount')
            ->from('Pool', 'p')
            ->innerJoin('p', 'Entry', 'e', 'p.id = e.poolId')
            ->where('p.listurl = :listurl')
            ->andWhere('wishlist_updated = 1')
            ->setParameter(':listurl', $listUrl);
        $viewedCount = $this->dbal->createQueryBuilder()
            ->select('count(e.id) AS viewedCount')
            ->from('Pool', 'p')
            ->innerJoin('p', 'Entry', 'e', 'p.id = e.poolid')
            ->where('p.listurl = :listurl')
            ->andWhere('viewdate is not null')
            ->setParameter(':listurl', $listUrl);

        return [
            'pool' => $pool->execute()->fetchAll(),
            'participantCount' => $participantCount->execute()->fetchAll(),
            'wishlistCount' => $wishlistCount->execute()->fetchAll(),
            'viewedCount' => $viewedCount->execute()->fetchAll(),
        ];
    }

    /**
     * @param $entryId
     *
     * @return mixed
     */
    public function findBuddyByEntryId($entryId)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('e.id')
            ->from('Entry', 'e')
            ->where('e.entryId = :entryId')
            ->setParameter('entryId', $entryId);

        return $query->execute()->fetchAll();
    }

    /**
     * @param $poolId
     *
     * @return mixed
     */
    public function findAdminIdByPoolId($poolId)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('e.id')
            ->from('Entry', 'e')
            ->where('e.poolId = :poolId')
            ->andWhere('e.poolAdmin = 1')
            ->setParameter('poolId', $poolId);

        return $query->execute()->fetchAll();
    }
}
