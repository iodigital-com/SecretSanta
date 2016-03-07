<?php

namespace Intracto\SecretSantaBundle\Query;

use Doctrine\DBAL\Connection;

class EntryReportQuery
{
    private $dbal;
    private $poolReportQuery;
    private $featuredYearsQuery;

    /**
     * @param Connection $dbal
     */
    public function __construct(Connection $dbal, $poolReportQuery, $featuredYearsQuery)
    {
        $this->dbal = $dbal;
        $this->poolReportQuery = $poolReportQuery;
        $this->featuredYearsQuery = $featuredYearsQuery;
    }

    /**
     * @param PoolYear $poolYear
     * @return array
     */
    public function countConfirmedEntries(PoolYear $poolYear)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(p.id) AS confirmedEntryCount')
            ->from('Pool', 'p')
            ->innerjoin('p', 'Entry', 'e', 'p.id = e.poolId')
            ->where('p.sentdate >= :firstDay')
            ->andWhere('p.sentdate < :lastDay')
            ->andWhere('e.viewdate IS NOT NULL')
            ->setParameter('firstDay', $poolYear->getStart()->format('Y-m-d H:i:s'))
            ->setParameter('lastDay', $poolYear->getEnd()->format('Y-m-d H:i:s'));

        return $query->execute()->fetchAll();
    }

    /**
     * @param \DateTime $date
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
     * @param PoolYear $poolYear
     * @return array
     */
    public function countDistinctEntries(PoolYear $poolYear)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(distinct e.email) AS distinctEntryCount')
            ->from('Pool', 'p')
            ->innerJoin('p', 'Entry', 'e', 'p.id = e.poolId')
            ->where('p.sentdate >= :firstDay')
            ->andWhere('p.sentdate < :lastDay')
            ->setParameter('firstDay', $poolYear->getStart()->format('Y-m-d H:i:s'))
            ->setParameter('lastDay', $poolYear->getEnd()->format('Y-m-d H:i:s'));

        return $query->execute()->fetchAll();
    }

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
     * @param PoolYear $poolYear
     * @return array
     */
    public function queryDataForMonthlyEntryChart(PoolYear $poolYear)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(p.id) AS accumulatedEntryCountByMonth, p.sentdate AS month')
            ->from('Pool', 'p')
            ->innerJoin('p', 'Entry', 'e', 'p.id = e.poolId')
            ->where('p.sentdate >= :firstDay')
            ->andWhere('p.sentdate < :lastDay')
            ->groupBy('month(p.sentdate)')
            ->orderBy('month(p.sentdate) < 4, month(p.sentdate)')
            ->setParameter('firstDay', $poolYear->getStart()->format('Y-m-d H:i:s'))
            ->setParameter('lastDay', $poolYear->getEnd()->format('Y-m-d H:i:s'));

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
            $lastDay = \DateTime::createFromFormat('Y-m-d', $year + 1 . '-04-01')->format('Y-m-d H:i:s');

            $query = $this->dbal->createQueryBuilder()
                ->select('count(p.id) AS accumulatedEntryCountByYear')
                ->from('Pool', 'p')
                ->innerJoin('p', 'Entry', 'e', 'p.id = e.poolId')
                ->where('p.sentdate IS NOT NULL')
                ->andWhere('p.sentdate < :lastDay')
                ->setParameter('lastDay', $lastDay);

            $chartData = $query->execute()->fetchAll();

            $entry = [
                'year' => $year,
                'entry' => $chartData
            ];

            array_push($entryChartData, $entry);
        }

        return $entryChartData;
    }

    /**
     * @param \DateTime $date
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
     * @param PoolYear $poolYear
     * @return float
     */
    public function calculateAverageEntriesPerPool(PoolYear $poolYear)
    {
        $pools = $this->poolReportQuery->countPools($poolYear);
        $entries = $this->countEntries($poolYear);

        if ($pools[0]['poolCount'] != 0 || $entries[0]['entryCount'] != 0) {
            return implode($entries[0]) / implode($pools[0]);
        }

        throw new NoResultException();
    }

    /**
     * @param PoolYear $poolYear
     * @return mixed
     */
    public function countEntries(PoolYear $poolYear)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(p.id) AS entryCount')
            ->from('Pool', 'p')
            ->innerjoin('p', 'Entry', 'e', 'p.id = e.poolId')
            ->where('p.sentdate >= :firstDay')
            ->andWhere('p.sentdate < :lastDay')
            ->setParameter('firstDay', $poolYear->getStart()->format('Y-m-d H:i:s'))
            ->setParameter('lastDay', $poolYear->getEnd()->format('Y-m-d H:i:s'));

        return $query->execute()->fetchAll();
    }

    /**
     * @param \DateTime $date
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
}