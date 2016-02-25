<?php

namespace Intracto\SecretSantaBundle\Helper;

use Symfony\Component\Validator\Constraints\Type;

class ReportingHelper
{
    private $doctrine;

    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getFullPullReport()
    {
        /** @var \Doctrine\DBAL\Connection $dbal */
        $dbal = $this->doctrine->getConnection();

        $pools = $dbal->fetchAll('SELECT count(*) as poolCount FROM Pool');
        $entries = $dbal->fetchAll('SELECT count(*) as entryCount FROM Entry JOIN Pool on Pool.id = Entry.poolId');
        $wishlists = $dbal->fetchAll('SELECT count(*) as wishListCount FROM Entry JOIN Pool on Pool.id = Entry.poolId WHERE wishlist_updated = TRUE');

        if ($pools[0]['poolCount'] != 0) {
            $entry_average = number_format(implode($entries[0]) / implode($pools[0]), 2);
        } else {
            $entry_average = number_format(0);
        }

        if ($entries[0]['entryCount'] != 0) {
            $wishlist_average = number_format((implode($wishlists[0]) / implode($entries[0])) * 100, 2);
        } else {
            $wishlist_average = number_format(0);
        }

        $featured_years = $dbal->fetchAll('SELECT DISTINCT year(sentdate) as featured_year FROM Pool WHERE year(sentdate) IS NOT NULL ORDER BY year(sentdate) DESC');

        return $data = [
            'pools' => $pools,
            'entries' => $entries,
            'wishlists' => $wishlists,
            'featured_years' => $featured_years,
            'entry_average' => $entry_average,
            'wishlist_average' => $wishlist_average
        ];
    }

    public function getPullReport($year)
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

        $featured_years = $dbal->fetchAll('SELECT DISTINCT year(sentdate) as featured_year FROM Pool WHERE year(sentdate) IS NOT NULL ORDER BY year(sentdate) DESC');

        if ($pools[0]['poolCount'] != 0) {
            $entry_average = number_format(implode($entries[0]) / implode($pools[0]), 2);
        } else {
            $entry_average = number_format(0, 2);
        }

        if ($entries[0]['entryCount'] != 0) {
            $wishlist_average = number_format((implode($wishlists[0]) / implode($entries[0])) * 100, 2);
        } else {
            $wishlist_average = number_format(0, 2);
        }

        return $data = [
            'pools' => $pools,
            'entries' => $entries,
            'wishlists' => $wishlists,
            'featured_years' => $featured_years,
            'entry_average' => $entry_average,
            'wishlist_average' => $wishlist_average,
            'firstDay' => $firstDay,
            'lastDay' => $lastDay
        ];
    }
}