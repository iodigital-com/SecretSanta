<?php

namespace Intracto\SecretSantaBundle\Query;

use Doctrine\DBAL\Connection;

class WishlistReportQuery
{
    private $dbal;
    private $entryReportQueries;

    /**
     * @param Connection $dbal
     */
    public function __construct(Connection $dbal, $entryReportQueries)
    {
        $this->dbal = $dbal;
        $this->entryReportQueries = $entryReportQueries;
    }

    /**
     * @param $firstDay
     * @param $lastDay
     * @return float
     */
    public function calculateCompletedWishlists($firstDay = null, $lastDay = null)
    {
        if ($firstDay != null && $lastDay != null) {
            $wishlists = $this->countWishlists($firstDay, $lastDay);
            $entries = $this->entryReportQueries->countEntries($firstDay, $lastDay);

            if ($entries[0]['entryCount'] != 0) {
                return (implode($wishlists[0]) / implode($entries[0])) * 100;
            }

            throw new NoResultException();
        }

        $wishlists = $this->countWishlists();
        $entries = $this->entryReportQueries->countEntries();

        if ($entries[0]['entryCount'] != 0) {
            return (implode($wishlists[0]) / implode($entries[0])) * 100;
        }

        throw new NoResultException();
    }

    /**
     * @param $firstDay
     * @param $lastDay
     * @return array
     */
    private function countWishlists($firstDay = null, $lastDay = null)
    {
        if($firstDay != null && $lastDay != null) {
            return $this->dbal->fetchAll(
                'SELECT count(*) as wishlistCount
                FROM Pool p
                JOIN Entry e on p.id = e.poolId
                WHERE p.sentdate >= :firstDay AND p.sentdate < :lastDay AND e.wishlist_updated = TRUE',
                ['firstDay' => $firstDay->format('Y-m-d H:i:s'), 'lastDay' => $lastDay->format('Y-m-d H:i:s')]
            );
        }

        return $this->dbal->fetchAll(
            'SELECT count(*) as wishlistCount
            FROM Entry
            JOIN Pool on Pool.id = Entry.poolId
            WHERE wishlist_updated = TRUE'
        );
    }

    /**
     * @param $lastDay
     * @return float
     */
    public function calculateCompletedWishlistsUntilDate($lastDay)
    {
        $totalWishlists = $this->countAllWishlistsUntilDate($lastDay);
        $totalEntries = $this->entryReportQueries->countAllEntriesUntilDate($lastDay);

        if ($totalEntries[0]['totalEntryCount'] != 0) {
            return (implode($totalWishlists[0]) / implode($totalEntries[0])) * 100;
        }

        throw new NoResultException();
    }

    /**
     * @param $lastDay
     * @return array
     */
    private function countAllWishlistsUntilDate($lastDay)
    {
        return $this->dbal->fetchAll(
            'SELECT count(*) as totalWishlistCount
            FROM Pool p
            JOIN Entry e ON p.id = e.poolId
            WHERE p.sentdate < :lastDay AND e.wishlist_updated = TRUE',
            ['lastDay' => $lastDay->format('Y-m-d H:i:s')]
        );
    }
}