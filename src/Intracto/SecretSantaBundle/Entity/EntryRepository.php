<?php

namespace Intracto\SecretSantaBundle\Entity;

use Doctrine\ORM\EntityRepository;

class EntryRepository extends EntityRepository
{
    /**
     * @return Entry[]
     */
    public function findAfter(\DateTime $startDate)
    {
        $query = $this->_em->createQuery('
            SELECT entry
            FROM IntractoSecretSantaBundle:Entry entry
            JOIN entry.pool pool
            JOIN entry.entry peer
            WHERE pool.sentdate >= :startDate
              AND peer.wishlist IS NOT NULL
        ');
        $query->setParameter('startDate', $startDate, \Doctrine\DBAL\Types\Type::DATETIME);

        return $query->getResult();
    }

    /**
     * @return Entry[]
     */
    public function findBatchForWishlistNotification($batchSize = 50)
    {
        $query = $this->_em->createQuery('
            SELECT entry
            FROM IntractoSecretSantaBundle:Entry entry
            JOIN entry.entry peer
            WHERE peer.wishlist_updated = 1
        ')->setMaxResults($batchSize);

        return $query->getResult();
    }

    /**
     * Find number of entries that haven't been watched yet in Pools which were sent
     * out more than two weeks ago and the party date is max six weeks in the
     * future.
     *
     * @return Entry[]
     */
    public function findBatchToRemindToViewEntry($batchSize)
    {
        $today = new \DateTime();
        $twoWeeksAgo = new \DateTime('now - 2 weeks');
        $sixWeeksFromNow = new \DateTime('now + 6 weeks');

        $query = $this->_em->createQuery('
            SELECT entry
            FROM IntractoSecretSantaBundle:Entry entry
              JOIN entry.pool pool
            WHERE entry.viewdate IS NULL
              AND entry.url IS NOT NULL
              AND pool.created = 1
              AND pool.eventdate > :today
              AND pool.eventdate < :sixWeeksFromNow
              AND pool.sentdate < :twoWeeksAgo
              AND (entry.viewReminderSentTime IS NULL OR entry.viewReminderSentTime < :twoWeeksAgo)
        ')->setMaxResults($batchSize);

        $query->setParameter('today', $today, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('twoWeeksAgo', $twoWeeksAgo, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('sixWeeksFromNow', $sixWeeksFromNow, \Doctrine\DBAL\Types\Type::DATETIME);

        return $query->getResult();
    }

    /**
     * Find number of entries that havean empty wishlit in Pools which were sent out
     * more than two weeks ago and the party date is max six weeks in the future.
     *
     * @return Entry[]
     */
    public function findBatchToRemindOfEmptyWishlist($batchSize)
    {
        $today = new \DateTime();
        $twoWeeksAgo = new \DateTime('now - 2 weeks');
        $sixWeeksFromNow = new \DateTime('now + 6 weeks');

        $query = $this->_em->createQuery('
            SELECT entry
            FROM IntractoSecretSantaBundle:Entry entry
              JOIN entry.pool pool
            WHERE entry.wishlist_updated = 0
              AND entry.url IS NOT NULL
              AND pool.created = 1
              AND pool.eventdate > :today
              AND pool.eventdate < :sixWeeksFromNow
              AND pool.sentdate < :twoWeeksAgo
              AND (entry.updateWishlistReminderSentTime IS NULL OR entry.updateWishlistReminderSentTime < :twoWeeksAgo)
        ')->setMaxResults($batchSize);

        $query->setParameter('today', $today, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('twoWeeksAgo', $twoWeeksAgo, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('sixWeeksFromNow', $sixWeeksFromNow, \Doctrine\DBAL\Types\Type::DATETIME);

        return $query->getResult();
    }
}
