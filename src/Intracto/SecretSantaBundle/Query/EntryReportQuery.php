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
     * @param $firstDay
     * @param $lastDay
     * @return array
     */
    public function countConfirmedEntries($firstDay = null, $lastDay = null)
    {
        if ($firstDay != null && $lastDay != null) {
            return $this->dbal->fetchAll(
                'SELECT count(*) AS confirmedEntryCount
                FROM Pool p
                JOIN Entry e ON p.id = e.poolId
                WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay AND e.viewdate IS NOT NULL',
                ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
            );
        }

        if ($lastDay != null) {
            return $this->dbal->fetchAll(
                'SELECT count(*) AS ConfirmedEntryCount
                FROM Pool p
                JOIN Entry e ON p.id = e.poolId
                WHERE p.sentdate < :lastDay AND e.viewdate IS NOT NULL',
                ['lastDay' => $lastDay->format('Y-m-d H:i:s')]
            );
        }

        return $this->dbal->fetchAll(
            'SELECT count(*) as confirmedEntryCount
            FROM Entry
            WHERE viewdate IS NOT NULL'
        );
    }

    /**
     * @param $firstDay
     * @param $lastDay
     * @return array
     */
    public function countDistinctEntries($firstDay = null, $lastDay = null)
    {
        if ($firstDay != null && $lastDay != null) {
            return $this->dbal->fetchAll(
                'SELECT count(distinct(e.email)) as distinctEntryCount
                FROM Pool p
                JOIN Entry e ON p.id = e.poolId
                WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay',
                ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
            );
        }

        if ($lastDay != null) {
            return $this->dbal->fetchAll(
                'SELECT count(distinct(e.email)) as distinctEntryCount
                FROM Pool p
                JOIN Entry e on p.id = e.poolId
                WHERE p.sentdate < :lastDay',
                ['lastDay' => $lastDay->format('Y-m-d H:i:s')]
            );
        }

        return $this->dbal->fetchAll(
            'SELECT count(distinct(Entry.email)) as distinctEntryCount
            FROM Pool
            JOIN Entry on Pool.id = Entry.poolId'
        );
    }

    /**
     * @param $firstDay
     * @param $lastDay
     * @return array
     */
    public function queryDataForEntryChart($firstDay = null, $lastDay = null)
    {
        if ($firstDay != null && $lastDay != null) {
            return $this->dbal->fetchAll(
                'SELECT count(*) as growthEntries, p.sentdate as month
                FROM Pool p
                JOIN Entry e ON p.id = e.poolId
                WHERE p.sentdate >= :firstDay and p.sentdate < :lastDay
                GROUP BY month(p.sentdate)
                ORDER BY month(p.sentdate) < 4, month(p.sentdate)',
                ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
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
                ['lastDay' => $lastDay->format('Y-m-d H:i:s')]
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
     * @param $lastDay
     * @return array
     */
    public function queryAllDataForEntryChart($lastDay)
    {
        $totalEntryChartData = $this->dbal->fetchAll(
            'SELECT count(*) as totalEntryCount, p.sentdate as month
            FROM Pool p
            JOIN Entry e ON p.id = e.poolId
            WHERE p.sentdate < :lastDay
            GROUP BY year(p.sentdate), month(p.sentdate)',
            ['lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );

        $accumulatedEntryCounter = 0;

        foreach ($totalEntryChartData as &$entryCount) {
            $accumulatedEntryCounter += $entryCount['totalEntryCount'];
            $entryCount['totalEntryCount'] = $accumulatedEntryCounter;
        }

        return $totalEntryChartData;
    }

    /**
     * @param $firstDay
     * @param $lastDay
     * @return float
     */
    public function calculateAverageEntriesPerPool($firstDay = null, $lastDay = null)
    {
        if ($firstDay != null && $lastDay != null) {
            $pools = $this->poolReportQuery->countPools($firstDay, $lastDay);
            $entries = $this->countEntries($firstDay, $lastDay);

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
     * @param $firstDay
     * @param $lastDay
     * @return array
     */
    public function countEntries($firstDay = null, $lastDay = null)
    {
        if ($firstDay != null && $lastDay != null) {
            return $this->dbal->fetchAll(
                'SELECT count(*) AS entryCount
                FROM Pool p
                JOIN Entry e ON p.id = e.poolId
                WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay',
                ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
            );
        }

        return $this->dbal->fetchAll(
            'SELECT count(*) as entryCount
            FROM Pool
            JOIN Entry on Pool.id = Entry.poolId'
        );
    }

    /**
     * @param $lastDay
     * @return float
     */
    public function calculateAverageEntriesPerPoolUntilDate($lastDay)
    {
        $totalPools = $this->poolReportQuery->countAllPoolsUntilDate($lastDay);
        $totalEntries = $this->countAllEntriesUntilDate($lastDay);

        if ($totalPools[0]['poolCount'] != 0) {
            return implode($totalEntries[0]) / implode($totalPools[0]);
        }

        throw new NoResultException();
    }

    /**
     * @param $lastDay
     * @return array
     */
    public function countAllEntriesUntilDate($lastDay)
    {
        return $this->dbal->fetchAll(
            'SELECT count(*) AS totalEntryCount
            FROM Pool p
            JOIN Entry e ON p.id = e.poolId
            WHERE p.sentdate < :lastDay',
            ['lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );
    }

}