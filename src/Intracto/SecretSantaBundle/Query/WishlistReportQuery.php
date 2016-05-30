<?php

namespace Intracto\SecretSantaBundle\Query;

use Doctrine\DBAL\Connection;

class WishlistReportQuery
{
    /** @var Connection */
    private $dbal;
    /** @var EntryReportQuery */
    private $entryReportQuery;

    /**
     * @param Connection       $dbal
     * @param EntryReportQuery $entryReportQuery
     */
    public function __construct(Connection $dbal, EntryReportQuery $entryReportQuery)
    {
        $this->dbal = $dbal;
        $this->entryReportQuery = $entryReportQuery;
    }

    /**
     * @param Season $season
     *
     * @return float
     */
    public function calculateCompletedWishlists(Season $season)
    {
        $wishlists = $this->countWishlists($season);
        $entries = $this->entryReportQuery->countEntries($season);

        if ($entries[0]['entryCount'] != 0) {
            return (implode($wishlists[0]) / implode($entries[0])) * 100;
        }

        throw new NoResultException();
    }

    /**
     * @param Season $season
     *
     * @return mixed
     */
    private function countWishlists(Season $season)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(p.id) AS wishlistCount')
            ->from('Pool', 'p')
            ->innerJoin('p', 'Entry', 'e', 'p.id = e.poolId')
            ->where('p.sentdate >= :firstDay')
            ->andWhere('p.sentdate < :lastDay')
            ->andWhere('e.wishlist_updated = TRUE')
            ->setParameter('firstDay', $season->getStart()->format('Y-m-d H:i:s'))
            ->setParameter('lastDay', $season->getEnd()->format('Y-m-d H:i:s'));

        return $query->execute()->fetchAll();
    }

    /**
     * @param \DateTime $date
     *
     * @return float
     */
    public function calculateCompletedWishlistsUntilDate(\DateTime $date)
    {
        $totalWishlists = $this->countAllWishlistsUntilDate($date);
        $totalEntries = $this->entryReportQuery->countAllEntriesUntilDate($date);

        if ($totalEntries[0]['totalEntryCount'] != 0) {
            return (implode($totalWishlists[0]) / implode($totalEntries[0])) * 100;
        }

        throw new NoResultException();
    }

    /**
     * @param \DateTime $date
     *
     * @return mixed
     */
    private function countAllWishlistsUntilDate(\DateTime $date)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(p.id) AS totalWishlistCount')
            ->from('Pool', 'p')
            ->innerJoin('p', 'Entry', 'e', 'p.id = e.poolId')
            ->where('p.sentdate < :lastDay')
            ->andWhere('e.wishlist_updated = TRUE')
            ->setParameter('lastDay', $date->format('Y-m-d H:i:s'));

        return $query->execute()->fetchAll();
    }

    /**
     * @param Season $season1
     * @param Season $season2
     *
     * @return float
     */
    public function calculateCompletedWishlistDifferenceBetweenSeasons(Season $season1, Season $season2)
    {
        $completedWishlistsSeason1 = $this->calculateCompletedWishlists($season1);
        try {
            $completedWishlistsSeason2 = $this->calculateCompletedWishlists($season2);
        } catch (\Exception $e) {
            return $completedWishlistsSeason1;
        }

        return $completedWishlistsSeason1 - $completedWishlistsSeason2;
    }
}
