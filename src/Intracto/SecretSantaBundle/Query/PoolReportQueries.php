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
            FROM Entry
            JOIN Pool on Pool.id = Entry.poolId'
        );
        $wishlists = $this->dbal->fetchAll(
            'SELECT count(*) as wishListCount
            FROM Entry
            JOIN Pool on Pool.id = Entry.poolId
            WHERE wishlist_updated = TRUE'
        );

        if ($pools[0]['poolCount'] != 0 || $entries[0]['entryCount']) {
            $entryAverage = number_format(implode($entries[0]) / implode($pools[0]), 2);
            $wishlistAverage = number_format((implode($wishlists[0]) / implode($entries[0])) * 100, 2);

            return [
                'pools' => $pools,
                'entries' => $entries,
                'wishlists' => $wishlists,
                'entry_average' => $entryAverage,
                'wishlist_average' => $wishlistAverage,
            ];
        } else {
            throw new NoResultException();
        }
    }

    /**
     * @param int $year
     * @return array
     */
    public function getPoolReport($year)
    {
        $firstDay = \DateTime::createFromFormat('Y-m-d', $year . '-01-01');
        $lastDay = \DateTime::createFromFormat('Y-m-d', $year + 1 . '-01-01');

        $pools = $this->dbal->fetchAll(
            'SELECT count(*) AS poolCount
            FROM Pool p
            WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay',
            ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );

        $entries = $this->dbal->fetchAll(
            'SELECT count(*) AS entryCount
            FROM Pool p
            JOIN Entry e ON p.id = e.poolId
            WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay',
            ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );

        $wishlists = $this->dbal->fetchAll(
            'SELECT count(*) as wishListCount
            FROM Pool p JOIN Entry e on p.id = e.poolId
            WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay AND e.wishlist_updated = TRUE',
            ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );

        if ($pools[0]['poolCount'] != 0 || $entries[0]['entryCount']) {
            $entryAverage = number_format(implode($entries[0]) / implode($pools[0]), 2);
            $wishlistAverage = number_format((implode($wishlists[0]) / implode($entries[0])) * 100, 2);

            return [
                'pools' => $pools,
                'entries' => $entries,
                'wishlists' => $wishlists,
                'entry_average' => $entryAverage,
                'wishlist_average' => $wishlistAverage,
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
            array_push($featuredYears, $f['featured_year']);
        }

        return [
            'featured_years' => $featuredYears,
        ];
    }
}