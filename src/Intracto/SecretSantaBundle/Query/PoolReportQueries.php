<?php

namespace Intracto\SecretSantaBundle\Query;

use Doctrine\DBAL\Connection;

class PoolReportQueries
{
    private $dbal;

    /**
     * @param Connection $dbal
     */
    public function __construct(Connection $dbal)
    {
        $this->dbal = $dbal;
    }

    /**
     * @return array
     * @throws NoResultException
     */
    public function getFullPoolReport()
    {
        $pools = $this->dbal->fetchAll(
            'SELECT count(*) as poolCount
            FROM Pool'
        );

        $entries = $this->dbal->fetchAll(
            'SELECT count(*) as entryCount
            FROM Pool
            JOIN Entry on Pool.id = Entry.poolId'
        );

        $distintEntries = $this->dbal->fetchAll(
            'SELECT count(distinct(Entry.email)) as distinctEntryCount
            FROM Pool
            JOIN Entry on Pool.id = Entry.poolId'
        );

        $wishlists = $this->dbal->fetchAll(
            'SELECT count(*) as wishListCount
            FROM Entry
            JOIN Pool on Pool.id = Entry.poolId
            WHERE wishlist_updated = TRUE'
        );

        $lineChartData = $this->getChartData();

        if ($pools[0]['poolCount'] != 0 || $entries[0]['entryCount']) {
            $entryAverage = round(implode($entries[0]) / implode($pools[0]));
            $wishlistAverage = number_format((implode($wishlists[0]) / implode($entries[0])) * 100, 2);

            return [
                'pools' => $pools,
                'entries' => $entries,
                'distinct_entries' => $distintEntries,
                'wishlists' => $wishlists,
                'entry_average' => $entryAverage,
                'wishlist_average' => $wishlistAverage,
                'linechart_data' => $lineChartData,
            ];
        } else {
            throw new NoResultException();
        }
    }

    /**
     * @param int $year
     * @return array
     * @throws NoResultException
     */
    public function getPoolReport($year)
    {
        $firstDay = \DateTime::createFromFormat('Y-m-d', $year . '-04-01');
        $lastDay = \DateTime::createFromFormat('Y-m-d', $year + 1 . '-04-01');

        $pools = $this->dbal->fetchAll(
            'SELECT count(*) AS poolCount
            FROM Pool p
            WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay',
            ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );

        $totalPools = $this->dbal->fetchAll(
            'SELECT count(*) AS totalPoolCount
            FROM Pool p
            WHERE p.sentdate < :lastDay',
            ['lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );

        $entries = $this->dbal->fetchAll(
            'SELECT count(*) AS entryCount
            FROM Pool p
            JOIN Entry e ON p.id = e.poolId
            WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay',
            ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );

        $totalEntries = $this->dbal->fetchAll(
            'SELECT count(*) AS totalEntryCount
            FROM Pool p
            JOIN Entry e ON p.id = e.poolId
            WHERE p.sentdate < :lastDay',
            ['lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );

        $distinctEntries = $this->dbal->fetchAll(
            'SELECT count(distinct(e.email)) as distinctEntryCount
            FROM Pool p
            JOIN Entry e ON p.id = e.poolId
            WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay',
            ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );

        $totalDistinctEntries = $this->dbal->fetchAll(
            'SELECT count(distinct(e.email)) as totalDistinctEntryCount
            FROM Pool p
            JOIN Entry e on p.id = e.poolId
            WHERE p.sentdate < :lastDay',
            ['lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );

        $wishlists = $this->dbal->fetchAll(
            'SELECT count(*) as wishListCount
            FROM Pool p JOIN Entry e on p.id = e.poolId
            WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay AND e.wishlist_updated = TRUE',
            ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );

        $totalWishLists = $this->dbal->fetchAll(
            'SELECT count(*) as totalWishListCount
            FROM Pool p
            JOIN Entry e ON p.id = e.poolId
            WHERE p.sentdate < :lastDay AND e.wishlist_updated = TRUE',
            ['lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );

        $lineChartPools = $this->dbal->fetchAll(
            'SELECT count(*) as growthPool, p.sentdate as month
            FROM Pool p
            WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay
            GROUP BY month(p.sentdate)
            ORDER BY month(p.sentdate) < 4, month(p.sentdate)',
            ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );

        $lineChartEntries = $this->dbal->fetchAll(
            'SELECT count(*) as growthEntries, p.sentdate as month
            FROM Pool p
            JOIN Entry e ON p.id = e.poolId
            WHERE p.sentdate >= :firstDay and p.sentdate < :lastDay
            GROUP BY month(p.sentdate)
            ORDER BY month(p.sentdate) < 4, month(p.sentdate)',
            ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );

        $totalChartPools = $this->dbal->fetchAll(
            'SELECT count(*) as totalPoolCount, p.sentdate as month
            FROM Pool p
            JOIN Entry e ON p.id = e.poolId
            WHERE p.sentdate < :lastDay
            GROUP BY year(p.sentdate), month(p.sentdate)',
            ['lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );

        $totalChartEntries = $this->dbal->fetchAll(
            'SELECT count(*) as totalEntryCount, p.sentdate as month
            FROM Pool p
            JOIN Entry e ON p.id = e.poolId
            WHERE p.sentdate < :lastDay
            GROUP BY year(p.sentdate), month(p.sentdate)',
            ['lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );

        if ($pools[0]['poolCount'] != 0 || $entries[0]['entryCount']) {
            $entryAverage = implode($entries[0]) / implode($pools[0]);
            $totalEntryAverage = implode($totalEntries[0]) / implode($totalPools[0]);
            $wishlistAverage = implode($wishlists[0]) / implode($entries[0]) * 100;
            $totalWishListAverage = (implode($totalWishLists[0]) / implode($totalEntries[0])) * 100;

            return [
                'pools' => $pools,
                'total_pools' => $totalPools,
                'entries' => $entries,
                'total_entries' => $totalEntries,
                'distinct_entries' => $distinctEntries,
                'total_distinct_entries' => $totalDistinctEntries,
                'wishlists' => $wishlists,
                'total_wishlists' => $totalWishLists,
                'entry_average' => $entryAverage,
                'total_entry_average' => $totalEntryAverage,
                'wishlist_average' => $wishlistAverage,
                'total_wishlist_average' => $totalWishListAverage,
                'linechart_pools' => $lineChartPools,
                'linechart_entries' => $lineChartEntries,
                'total_chart_pools' => $totalChartPools,
                'total_chart_entries' => $totalChartEntries,
            ];
        } else {
            throw new NoResultException();
        }
    }

    /**
     * @return array
     */
    public function getFeaturedYears()
    {
        $yearsQuery = $this->dbal->fetchAll(
            'SELECT DISTINCT year(sentdate) as featured_year
            FROM Pool
            WHERE year(sentdate) IS NOT NULL
            ORDER BY year(sentdate) DESC'
        );

        $featuredYears = [];

        foreach ($yearsQuery as $f) {
            $checkDate = \DateTime::createFromFormat('Y-m-d', $f['featured_year'] . '-04-01');
            $dateNow = new \DateTime();

            if ($dateNow >= $checkDate) {
                array_push($featuredYears, $f['featured_year']);
            }
        }

        $featuredYears = array_reverse($featuredYears);

        return [
            'featured_years' => $featuredYears,
        ];
    }

    public function getChartData()
    {
        $featuredYears = $this->getFeaturedYears();
        $poolData = [];
        $entryData = [];

        foreach($featuredYears['featured_years'] as $year){
            $firstDay = \DateTime::createFromFormat('Y-m-d', $year . '-04-01');
            $lastDay = \DateTime::createFromFormat('Y-m-d', $year + 1 . '-04-01');

            $pools = $this->dbal->fetchAll(
                'SELECT count(*) AS poolCount
                FROM Pool p
                WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay',
                ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
            );

            array_push($poolData, $pools);

            $entries = $this->dbal->fetchAll(
                'SELECT count(*) AS entryCount
                FROM Pool p
                JOIN Entry e ON p.id = e.poolId
                WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay',
                ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
            );

            array_push($entryData, $entries);
        }

        return [
            'data' => $poolData,
            'entry_data' => $entryData,
        ];
    }
}