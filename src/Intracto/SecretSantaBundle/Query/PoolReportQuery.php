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
     * @param null $firstDay
     * @param null $lastDay
     * @return array
     */
    public function countPools($firstDay = null, $lastDay = null)
    {
        if ($firstDay != null && $lastDay != null) {
            return $this->dbal->fetchAll(
                'SELECT count(*) AS poolCount
                FROM Pool p
                WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay',
                ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
            );
        }

        return $this->dbal->fetchAll(
            'SELECT count(*) as poolCount
            FROM Pool'
        );
    }

    /**
     * @param $lastDay
     * @return array
     */
    public function countAllPoolsUntilDate($lastDay)
    {
        return $this->dbal->fetchAll(
            'SELECT count(*) AS poolCount
            FROM Pool p
            WHERE p.sentdate < :lastDay',
            ['lastDay' => $lastDay->format('Y-m-d H:i:s')]
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
     * @param $firstDay
     * @param $lastDay
     * @return array
     */
    public function queryDataForPoolChart($firstDay = null, $lastDay = null)
    {
        if ($firstDay != null && $lastDay != null) {
            return $this->dbal->fetchAll(
                'SELECT count(*) as growthPool, p.sentdate as month
                FROM Pool p
                WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay
                GROUP BY month(p.sentdate)
                ORDER BY month(p.sentdate) < 4, month(p.sentdate)',
                ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
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
                ['lastDay' => $lastDay->format('Y-m-d H:i:s')]
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
     * @param $lastDay
     * @return array
     */
    public function queryAllDataUntilDateForPoolChart($lastDay)
    {
        $totalPoolChartData = $this->dbal->fetchAll(
            'SELECT count(*) as totalPoolCount, p.sentdate as month
            FROM Pool p
            WHERE p.sentdate < :lastDay
            GROUP BY year(p.sentdate), month(p.sentdate)',
            ['lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );

        $accumulatedPoolCounter = 0;

        foreach ($totalPoolChartData as &$poolCount) {
            $accumulatedPoolCounter += $poolCount['totalPoolCount'];
            $poolCount['totalPoolCount'] = $accumulatedPoolCounter;
        }

        return $totalPoolChartData;
    }
}