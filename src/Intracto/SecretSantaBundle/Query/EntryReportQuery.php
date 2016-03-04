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
     * @param Period|null $period
     * @return array
     */
    public function countConfirmedEntries(Period $period = null)
    {
        if ($period != null) {
            return $this->dbal->fetchAll(
                'SELECT count(*) AS confirmedEntryCount
                FROM Pool p
                JOIN Entry e ON p.id = e.poolId
                WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay AND e.viewdate IS NOT NULL',
                ['firstDay' => $period->getStart(), 'lastDay' => $period->getEnd()]
            );
        }

        if ($period->getEnd() != null) {
            return $this->dbal->fetchAll(
                'SELECT count(*) AS ConfirmedEntryCount
                FROM Pool p
                JOIN Entry e ON p.id = e.poolId
                WHERE p.sentdate < :lastDay AND e.viewdate IS NOT NULL',
                ['lastDay' => $period->getEnd()]
            );
        }

        return $this->dbal->fetchAll(
            'SELECT count(*) as confirmedEntryCount
            FROM Entry
            WHERE viewdate IS NOT NULL'
        );
    }

    /**
     * @param Period|null $period
     * @return array
     */
    public function countDistinctEntries(Period $period= null)
    {
        if ($period != null) {
            return $this->dbal->fetchAll(
                'SELECT count(distinct(e.email)) as distinctEntryCount
                FROM Pool p
                JOIN Entry e ON p.id = e.poolId
                WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay',
                ['firstDay' => $period->getStart(), 'lastDay' => $period->getEnd()]
            );
        }

        if ($period->getEnd() != null) {
            return $this->dbal->fetchAll(
                'SELECT count(distinct(e.email)) as distinctEntryCount
                FROM Pool p
                JOIN Entry e on p.id = e.poolId
                WHERE p.sentdate < :lastDay',
                ['lastDay' => $period->getEnd()]
            );
        }

        return $this->dbal->fetchAll(
            'SELECT count(distinct(Entry.email)) as distinctEntryCount
            FROM Pool
            JOIN Entry on Pool.id = Entry.poolId'
        );
    }

    /**
     * @param Period|null $period
     * @return array
     */
    public function queryDataForEntryChart(Period $period = null)
    {
        if ($period != null) {
            return $this->dbal->fetchAll(
                'SELECT count(*) as growthEntries, p.sentdate as month
                FROM Pool p
                JOIN Entry e ON p.id = e.poolId
                WHERE p.sentdate >= :firstDay and p.sentdate < :lastDay
                GROUP BY month(p.sentdate)
                ORDER BY month(p.sentdate) < 4, month(p.sentdate)',
                ['firstDay' => $period->getStart(), 'lastDay' => $period->getEnd()]
            );
        }

        $featuredYears = $this->featuredYearsQuery->getFeaturedYears();
        $entryChartData = [];

        foreach ($featuredYears['featured_years'] as $year) {
            $lastDay = \DateTime::createFromFormat('Y-m-d', $year + 1 . '-04-01');

            $chartData = $this->dbal->fetchAll(
                'SELECT count(*) as growthEntries
                FROM Pool p
                JOIN Entry e ON p.id = e.poolId
                WHERE p.sentdate IS NOT NULL AND p.sentdate < :lastDay',
                ['lastDay' => $lastDay]
            );

            $entry = [
                'year' => $year,
                'entry' => $chartData
            ];

            array_push($entryChartData, $entry);
        }

        return $entryChartData;
    }

    /**
     * @param Period|null $period
     * @return array
     */
    public function queryAllDataForEntryChart(Period $period = null)
    {
        $totalEntryChartData = $this->dbal->fetchAll(
            'SELECT count(*) as totalEntryCount, p.sentdate as month
            FROM Pool p
            JOIN Entry e ON p.id = e.poolId
            WHERE p.sentdate < :lastDay
            GROUP BY year(p.sentdate), month(p.sentdate)',
            ['lastDay' => $period->getEnd()]
        );

        $accumulatedEntryCounter = 0;

        foreach ($totalEntryChartData as &$entryCount) {
            $accumulatedEntryCounter += $entryCount['totalEntryCount'];
            $entryCount['totalEntryCount'] = $accumulatedEntryCounter;
        }

        return $totalEntryChartData;
    }

    /**
     * @param Period|null $period
     * @return float
     */
    public function calculateAverageEntriesPerPool(Period $period = null)
    {
        if ($period != null) {
            $pools = $this->poolReportQuery->countPools($period);
            $entries = $this->countEntries($period);

            if ($pools[0]['poolCount'] != 0 || $entries[0]['entryCount'] != 0) {
                return implode($entries[0]) / implode($pools[0]);
            }

            throw new NoResultException();
        }

        $pools = $this->poolReportQuery->countPools();
        $entries = $this->countEntries();

        if ($pools[0]['poolCount'] != 0 || $entries[0]['entryCount'] != 0) {
            return implode($entries[0]) / implode($pools[0]);
        }

        throw new NoResultException();
    }

    /**
     * @param Period|null $period
     * @return array
     */
    public function countEntries(Period $period = null)
    {
        if ($period != null) {
            return $this->dbal->fetchAll(
                'SELECT count(*) AS entryCount
                FROM Pool p
                JOIN Entry e ON p.id = e.poolId
                WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay',
                ['firstDay' => $period->getStart(), 'lastDay' => $period->getEnd()]
            );
        }

        return $this->dbal->fetchAll(
            'SELECT count(*) as entryCount
            FROM Pool
            JOIN Entry on Pool.id = Entry.poolId'
        );
    }

    /**
     * @param Period|null $period
     * @return float
     */
    public function calculateAverageEntriesPerPoolUntilDate(Period $period = null)
    {
        $totalPools = $this->poolReportQuery->countAllPoolsUntilDate($period);
        $totalEntries = $this->countAllEntriesUntilDate($period);

        if ($totalPools[0]['poolCount'] != 0) {
            return implode($totalEntries[0]) / implode($totalPools[0]);
        }

        throw new NoResultException();
    }

    /**
     * @param Period|null $period
     * @return array
     */
    public function countAllEntriesUntilDate(Period $period = null)
    {
        return $this->dbal->fetchAll(
            'SELECT count(*) AS totalEntryCount
            FROM Pool p
            JOIN Entry e ON p.id = e.poolId
            WHERE p.sentdate < :lastDay',
            ['lastDay' => $period->getEnd()]
        );
    }

}