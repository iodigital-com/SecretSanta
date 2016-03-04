<?php

namespace Intracto\SecretSantaBundle\Query;

use Doctrine\DBAL\Connection;

class PoolReportQuery
{
    private $dbal;
    private $featuredYearsQuery;

    /**
     * @param Connection $dbal
     */
    public function __construct(Connection $dbal, $featuredYearsQuery)
    {
        $this->dbal = $dbal;
        $this->featuredYearsQuery = $featuredYearsQuery;
    }

    /**
     * @param Period|null $period
     * @return array
     */
    public function countPools(Period $period = null)
    {
        if ($period != null) {
            return $this->dbal->fetchAll(
                'SELECT count(*) AS poolCount
                FROM Pool p
                WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay',
                ['firstDay' => $period->getStart(), 'lastDay' => $period->getEnd()]
            );
        }

        return $this->dbal->fetchAll(
            'SELECT count(*) as poolCount
            FROM Pool'
        );
    }

    /**
     * @param Period|null $period
     * @return array
     */
    public function countAllPoolsUntilDate(Period $period = null)
    {
        return $this->dbal->fetchAll(
            'SELECT count(*) AS poolCount
            FROM Pool p
            WHERE p.sentdate < :lastDay',
            ['lastDay' => $period->getEnd()]
        );
    }

    /**
     * @return array
     */
    public function countConfirmedPools()
    {
        return $this->dbal->fetchAll(
            'SELECT count(*) as confirmedPoolCount
            FROM Pool
            WHERE sentdate IS NOT NULL'
        );
    }

    /**
     * @param Period|null $period
     * @return array
     */
    public function queryDataForPoolChart(Period $period = null)
    {
        if ($period != null) {
            return $this->dbal->fetchAll(
                'SELECT count(*) as growthPool, p.sentdate as month
                FROM Pool p
                WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay
                GROUP BY month(p.sentdate)
                ORDER BY month(p.sentdate) < 4, month(p.sentdate)',
                ['firstDay' => $period->getStart(), 'lastDay' => $period->getEnd()]
            );
        }

        $featuredYears = $this->featuredYearsQuery->getFeaturedYears();
        $poolChartData = [];

        foreach ($featuredYears['featured_years'] as $year) {
            $lastDay = \DateTime::createFromFormat('Y-m-d', $year + 1 . '-04-01');

            $chartData = $this->dbal->fetchAll(
                'SELECT count(*) as growthPool
                FROM Pool p
                WHERE p.sentdate IS NOT NULL AND p.sentdate < :lastDay',
                ['lastDay' => $lastDay]
            );

            $pool = [
                'year' => $year,
                'pool' => $chartData
            ];

            array_push($poolChartData, $pool);
        }

        return $poolChartData;
    }

    /**
     * @param Period|null $period
     * @return array
     */
    public function queryAllDataUntilDateForPoolChart(Period $period = null)
    {
        $totalPoolChartData = $this->dbal->fetchAll(
            'SELECT count(*) as totalPoolCount, p.sentdate as month
            FROM Pool p
            WHERE p.sentdate < :lastDay
            GROUP BY year(p.sentdate), month(p.sentdate)',
            ['lastDay' => $period->getEnd()]
        );

        $accumulatedPoolCounter = 0;

        foreach ($totalPoolChartData as &$poolCount) {
            $accumulatedPoolCounter += $poolCount['totalPoolCount'];
            $poolCount['totalPoolCount'] = $accumulatedPoolCounter;
        }

        return $totalPoolChartData;
    }
}