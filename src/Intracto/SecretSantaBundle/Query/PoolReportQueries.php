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
     */
    public function getFullPoolReport()
    {
        return [
            'pools' => $this->getFullPools(),
            'confirmed_pools' => $this->getFullConfirmedPools(),
            'entries' => $this->getFullEntries(),
            'distinct_entries' => $this->getFullDistinctEntries(),
            'confirmed_entries' => $this->getFullConfirmedEntries(),
            'entry_average' => $this->getFullEntryAverage(),
            'wishlist_average' => $this->getFullWishlistAverage(),
            'ip_usage' => $this->getFullIpUsage(),
            'full_pool_chart_data' => $this->getFullPoolChartData(),
            'full_entry_chart_data' => $this->getFullEntryChartData(),
        ];
    }

    /**
     * @return array
     */
    private function getFullPools()
    {
        return $this->dbal->fetchAll(
            'SELECT count(*) as poolCount
            FROM Pool'
        );
    }

    /**
     * @return array
     */
    private function getFullConfirmedPools()
    {
        return $this->dbal->fetchAll(
            'SELECT count(*) as confirmedPoolCount
            FROM Pool
            WHERE sentdate IS NOT NULL'
        );
    }

    /**
     * @return array
     */
    private function getFullEntries()
    {
        return $this->dbal->fetchAll(
            'SELECT count(*) as entryCount
            FROM Pool
            JOIN Entry on Pool.id = Entry.poolId'
        );
    }

    /**
     * @return array
     */
    private function getFullDistinctEntries()
    {
        return $this->dbal->fetchAll(
            'SELECT count(distinct(Entry.email)) as distinctEntryCount
            FROM Pool
            JOIN Entry on Pool.id = Entry.poolId'
        );
    }

    /**
     * @return array
     */
    private function getFullConfirmedEntries()
    {
        return $this->dbal->fetchAll(
            'SELECT count(*) as confirmedEntryCount
            FROM Entry
            WHERE viewdate IS NOT NULL'
        );
    }

    /**
     * @return float
     * @throws NoResultException
     */
    private function getFullEntryAverage()
    {
        $pools = $this->getFullPools();
        $entries = $this->getFullEntries();

        if ($pools[0]['poolCount'] != 0 || $entries[0]['entryCount'] != 0) {
            return implode($entries[0]) / implode($pools[0]);
        } else {
            throw new NoResultException();
        }
    }

    /**
     * @return float
     * @throws NoResultException
     */
    private function getFullWishlistAverage()
    {
        $wishlists = $this->getFullWishlists();
        $entries = $this->getFullEntries();

        if ($entries[0]['entryCount'] != 0) {
            return (implode($wishlists[0]) / implode($entries[0])) * 100;
        } else {
            throw new NoResultException();
        }
    }

    /**
     * @return array
     */
    private function getFullWishlists()
    {
        return $this->dbal->fetchAll(
            'SELECT count(*) as wishlistCount
            FROM Entry
            JOIN Pool on Pool.id = Entry.poolId
            WHERE wishlist_updated = TRUE'
        );
    }

    /**
     * @return array
     */
    public function getFullIpUsage()
    {
        $ipv4 = $this->getFullIpv4Usage();
        $ipv6 = $this->getFullIpv6Usage();

        if ($ipv4[0]['ipv4Count'] + $ipv6[0]['ipv6Count'] != 0) {
            $ipv4Percentage = $ipv4[0]['ipv4Count'] / ($ipv4[0]['ipv4Count'] + $ipv6[0]['ipv6Count']);
            $ipv6Percentage = $ipv6[0]['ipv6Count'] / ($ipv4[0]['ipv4Count'] + $ipv6[0]['ipv6Count']);

            return [
                'ipv4_percentage' => $ipv4Percentage,
                'ipv6_percentage' => $ipv6Percentage,
            ];
        } else {
            return [];
        }
    }

    /**
     * @return array
     */
    private function getFullIpv4Usage()
    {
        return $this->dbal->fetchAll(
            'SELECT count(ipv4) as ipv4Count
            FROM Entry
            WHERE ipv4 IS NOT NULL'
        );
    }

    /**
     * @return array
     */
    public function getFullIpv6Usage()
    {
        return $this->dbal->fetchAll(
            'SELECT count(ipv6) as ipv6Count
            FROM Entry
            WHERE ipv6 IS NOT NULL'
        );
    }

    /**
     * @return array
     */
    private function getFullPoolChartData()
    {
        $poolChartData = $this->dbal->fetchAll(
            'SELECT count(*) as growthPool, p.sentdate as month
            FROM Pool p
            WHERE p.sentdate IS NOT NULL
            GROUP BY year(p.sentdate), month(p.sentdate)'
        );

        $accumulatedPoolCounter = 0;

        foreach ($poolChartData as &$poolCount) {
            $accumulatedPoolCounter += $poolCount['growthPool'];
            $poolCount['growthPool'] = $accumulatedPoolCounter;
        }

        return $poolChartData;
    }

    /**
     * @return array
     */
    private function getFullEntryChartData()
    {
        $entryChartData = $this->dbal->fetchAll(
            'SELECT count(*) as growthEntries, p.sentdate as month
            FROM Pool p
            JOIN Entry e ON p.id = e.poolId
            WHERE p.sentdate IS NOT NULL
            GROUP BY year(p.sentdate), month(p.sentdate)'
        );

        $accumulatedEntryCounter = 0;

        foreach ($entryChartData as &$entryCount) {
            $accumulatedEntryCounter += $entryCount['growthEntries'];
            $entryCount['growthEntries'] = $accumulatedEntryCounter;
        }

        return $entryChartData;
    }

    /**
     * @param int $year
     * @return array
     */
    public function getPoolReport($year)
    {
        $firstDay = \DateTime::createFromFormat('Y-m-d', $year . '-04-01');
        $lastDay = \DateTime::createFromFormat('Y-m-d', $year + 1 . '-04-01');

        return [
            'pools' => $this->getPools($firstDay, $lastDay),
            'total_pools' => $this->getTotalPools($lastDay),
            'entries' => $this->getEntries($firstDay, $lastDay),
            'total_entries' => $this->getTotalEntries($lastDay),
            'confirmed_entries' => $this->getConfirmedEntries($firstDay, $lastDay),
            'total_confirmed_entries' => $this->getTotalConfirmedEntries($lastDay),
            'distinct_entries' => $this->getDistinctEntries($firstDay, $lastDay),
            'total_distinct_entries' => $this->getTotalDistinctEntries($lastDay),
            'entry_average' => $this->getEntryAverage($firstDay, $lastDay),
            'total_entry_average' => $this->getTotalEntryAverage($lastDay),
            'wishlist_average' => $this->getWishlistAverage($firstDay, $lastDay),
            'total_wishlist_average' => $this->getTotalWishlistAverage($lastDay),
            'ip_usage' => $this->getIpUsage($firstDay, $lastDay),
            'pool_chart_data' => $this->getMonthPoolChartData($firstDay, $lastDay),
            'total_pool_chart_data' => $this->getTotalPoolChartData($lastDay),
            'entry_chart_data' => $this->getMonthEntryChartData($firstDay, $lastDay),
            'total_entry_chart_data' => $this->getTotalEntryChartData($lastDay),
        ];
    }

    /**
     * @param $firstDay
     * @param $lastDay
     * @return array
     */
    private function getPools($firstDay, $lastDay)
    {
        return $this->dbal->fetchAll(
            'SELECT count(*) AS poolCount
            FROM Pool p
            WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay',
            ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );
    }

    /**
     * @param $lastDay
     * @return array
     */
    private function getTotalPools($lastDay)
    {
        return $this->dbal->fetchAll(
            'SELECT count(*) AS totalPoolCount
            FROM Pool p
            WHERE p.sentdate < :lastDay',
            ['lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );
    }

    /**
     * @param $firstDay
     * @param $lastDay
     * @return array
     */
    private function getEntries($firstDay, $lastDay)
    {
        return $this->dbal->fetchAll(
            'SELECT count(*) AS entryCount
            FROM Pool p
            JOIN Entry e ON p.id = e.poolId
            WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay',
            ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );
    }

    /**
     * @param $lastDay
     * @return array
     */
    private function getTotalEntries($lastDay)
    {
        return $this->dbal->fetchAll(
            'SELECT count(*) AS totalEntryCount
            FROM Pool p
            JOIN Entry e ON p.id = e.poolId
            WHERE p.sentdate < :lastDay',
            ['lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );
    }

    /**
     * @param $firstDay
     * @param $lastDay
     * @return array
     */
    private function getConfirmedEntries($firstDay, $lastDay)
    {
        return $this->dbal->fetchAll(
            'SELECT count(*) AS confirmedEntryCount
            FROM Pool p
            JOIN Entry e ON p.id = e.poolId
            WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay AND e.viewdate IS NOT NULL',
            ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );
    }

    /**
     * @param $firstDay
     * @param $lastDay
     * @return array
     */
    private function getTotalConfirmedEntries($lastDay)
    {
        return $this->dbal->fetchAll(
            'SELECT count(*) AS totalConfirmedEntryCount
            FROM Pool p
            JOIN Entry e ON p.id = e.poolId
            WHERE p.sentdate < :lastDay AND e.viewdate IS NOT NULL',
            ['lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );
    }

    /**
     * @param $firstDay
     * @param $lastDay
     * @return array
     */
    private function getDistinctEntries($firstDay, $lastDay)
    {
        return $this->dbal->fetchAll(
            'SELECT count(distinct(e.email)) as distinctEntryCount
            FROM Pool p
            JOIN Entry e ON p.id = e.poolId
            WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay',
            ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );
    }

    /**
     * @param $lastDay
     * @return array
     */
    private function getTotalDistinctEntries($lastDay)
    {
        return $this->dbal->fetchAll(
            'SELECT count(distinct(e.email)) as totalDistinctEntryCount
            FROM Pool p
            JOIN Entry e on p.id = e.poolId
            WHERE p.sentdate < :lastDay',
            ['lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );
    }

    /**
     * @param $firstDay
     * @param $lastDay
     * @return float
     */
    private function getEntryAverage($firstDay, $lastDay)
    {
        $pools = $this->getPools($firstDay, $lastDay);
        $entries = $this->getEntries($firstDay, $lastDay);

        if ($pools[0]['poolCount'] != 0 || $entries[0]['entryCount'] != 0) {
            return implode($entries[0]) / implode($pools[0]);
        } else {
            throw new NoResultException();
        }
    }

    /**
     * @param $lastDay
     * @return float
     */
    private function getTotalEntryAverage($lastDay)
    {
        $totalPools = $this->getTotalPools($lastDay);
        $totalEntries = $this->getTotalEntries($lastDay);

        if ($totalPools[0]['totalPoolCount'] != 0) {
            return implode($totalEntries[0]) / implode($totalPools[0]);
        } else {
            throw new NoResultException();
        }
    }

    /**
     * @param $firstDay
     * @param $lastDay
     * @return float
     */
    private function getWishlistAverage($firstDay, $lastDay)
    {
        $wishlists = $this->getWishlists($firstDay, $lastDay);
        $entries = $this->getEntries($firstDay, $lastDay);

        if ($entries[0]['entryCount'] != 0) {
            return (implode($wishlists[0]) / implode($entries[0])) * 100;
        } else {
            throw new NoResultException();
        }
    }

    /**
     * @param $firstDay
     * @param $lastDay
     * @return array
     */
    private function getWishlists($firstDay, $lastDay)
    {
        return $this->dbal->fetchAll(
            'SELECT count(*) as wishlistCount
            FROM Pool p JOIN Entry e on p.id = e.poolId
            WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay AND e.wishlist_updated = TRUE',
            ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );
    }

    /**
     * @param $lastDay
     * @return float
     */
    private function getTotalWishlistAverage($lastDay)
    {
        $totalWishlists = $this->getTotalWishlists($lastDay);
        $totalEntries = $this->getTotalEntries($lastDay);

        if ($totalEntries[0]['totalEntryCount'] != 0) {
            return (implode($totalWishlists[0]) / implode($totalEntries[0])) * 100;
        } else {
            throw new NoResultException();
        }
    }

    /**
     * @param $lastDay
     * @return array
     */
    private function getTotalWishlists($lastDay)
    {
        return $this->dbal->fetchAll(
            'SELECT count(*) as totalWishlistCount
            FROM Pool p
            JOIN Entry e ON p.id = e.poolId
            WHERE p.sentdate < :lastDay AND e.wishlist_updated = TRUE',
            ['lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );
    }

    /**
     * @param $firstDay
     * @param $lastDay
     * @return array
     */
    private function getMonthPoolChartData($firstDay, $lastDay)
    {
        return $this->dbal->fetchAll(
            'SELECT count(*) as growthPool, p.sentdate as month
            FROM Pool p
            WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay
            GROUP BY month(p.sentdate)
            ORDER BY month(p.sentdate) < 4, month(p.sentdate)',
            ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );
    }

    /**
     * @param $lastDay
     * @return array
     */
    private function getTotalPoolChartData($lastDay)
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

    /**
     * @param $firstDay
     * @param $lastDay
     * @return array
     */
    private function getMonthEntryChartData($firstDay, $lastDay)
    {
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

    /**
     * @param $lastDay
     * @return array
     */
    private function getTotalEntryChartData($lastDay)
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
     * @return array
     */
    public function getIpUsage($firstDay, $lastDay)
    {
        $ipv4 = $this->getIpv4Usage($firstDay, $lastDay);
        $ipv6 = $this->getIpv6Usage($firstDay, $lastDay);

        if ($ipv4[0]['ipv4Count'] + $ipv6[0]['ipv6Count'] != 0) {
            $ipv4Percentage = $ipv4[0]['ipv4Count'] / ($ipv4[0]['ipv4Count'] + $ipv6[0]['ipv6Count']);
            $ipv6Percentage = $ipv6[0]['ipv6Count'] / ($ipv4[0]['ipv4Count'] + $ipv6[0]['ipv6Count']);

            return [
                'ipv4_percentage' => $ipv4Percentage,
                'ipv6_percentage' => $ipv6Percentage,
            ];
        } else {
            return [];
        }
    }

    /**
     * @param $firstDay
     * @param $lastDay
     * @return array
     */
    private function getIpv4Usage($firstDay, $lastDay)
    {
        return $this->dbal->fetchAll(
            'SELECT count(e.ipv4) as ipv4Count
            FROM Pool p
            JOIN Entry e ON p.id = e.poolId
            WHERE e.ipv4 IS NOT NULL AND p.sentdate >= :firstDay AND p.sentdate < :lastDay',
            ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );
    }

    /**
     * @param $firstDay
     * @param $lastDay
     * @return array
     */
    public function getIpv6Usage($firstDay, $lastDay)
    {
        return $this->dbal->fetchAll(
            'SELECT count(e.ipv6) as ipv6Count
            FROM Pool p
            JOIN Entry e ON p.id = e.poolId
            WHERE ipv6 IS NOT NULL AND p.sentdate >= :firstDay AND p.sentdate < :lastDay',
            ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );
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
}