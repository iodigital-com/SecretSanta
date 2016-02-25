<?php

namespace Intracto\SecretSantaBundle\Query;

class ReportQuery
{
    private $doctrine;

    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getFullPoolReport()
    {
        /** @var \Doctrine\DBAL\Connection $dbal */
        $dbal = $this->doctrine->getConnection();

        $pools = $dbal->fetchAll('SELECT count(*) as poolCount FROM Pool');
        $entries = $dbal->fetchAll('SELECT count(*) as entryCount FROM Entry JOIN Pool on Pool.id = Entry.poolId');
        $wishlists = $dbal->fetchAll('SELECT count(*) as wishListCount FROM Entry JOIN Pool on Pool.id = Entry.poolId WHERE wishlist_updated = TRUE');

        if ($pools[0]['poolCount'] != 0 || $entries[0]['entryCount']) {
            $entry_average = number_format(implode($entries[0]) / implode($pools[0]), 2);
            $wishlist_average = number_format((implode($wishlists[0]) / implode($entries[0])) * 100, 2);

            return $data = [
                'pools' => $pools,
                'entries' => $entries,
                'wishlists' => $wishlists,
                'entry_average' => $entry_average,
                'wishlist_average' => $wishlist_average
            ];
        } else {
            return $data = [];
        }
    }

    public function getPoolReport($year)
    {
        /** @var \Doctrine\DBAL\Connection $dbal */
        $dbal = $this->doctrine->getConnection();

        $firstDay = \DateTime::createFromFormat('Y-m-d', $year . '-01-01');
        $lastDay = \DateTime::createFromFormat('Y-m-d', $year + 1 . '-01-01');

        $pools = $dbal->fetchAll('SELECT count(*) AS poolCount FROM Pool p WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay',
            ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]);

        $entries = $dbal->fetchAll('SELECT count(*) AS entryCount FROM Pool p JOIN Entry e ON p.id = e.poolId WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay',
            ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]);

        $wishlists = $dbal->fetchAll('SELECT count(*) as wishListCount FROM Pool p JOIN Entry e on p.id = e.poolId WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay AND e.wishlist_updated = TRUE',
            ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]);

        if ($pools[0]['poolCount'] != 0 || $entries[0]['entryCount']) {
            $entry_average = number_format(implode($entries[0]) / implode($pools[0]), 2);
            $wishlist_average = number_format((implode($wishlists[0]) / implode($entries[0])) * 100, 2);

            return $data = [
                'pools' => $pools,
                'entries' => $entries,
                'wishlists' => $wishlists,
                'entry_average' => $entry_average,
                'wishlist_average' => $wishlist_average
            ];
        } else {
            return $data = [];
        }
    }

    public function getFeaturedYears()
    {
        /** @var \Doctrine\DBAL\Connection $dbal */
        $dbal = $this->doctrine->getConnection();

        $yearsQuery = $dbal->fetchAll('SELECT DISTINCT year(sentdate) as featured_year FROM Pool WHERE year(sentdate) IS NOT NULL ORDER BY year(sentdate) DESC');

        $featured_years = [];

        foreach ($yearsQuery as $f) {
            array_push($featured_years, $f['featured_year']);
        }

        return $data = [
            'featured_years' => $featured_years
        ];
    }
}