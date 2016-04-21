<?php

namespace Intracto\SecretSantaBundle\Query;

use Doctrine\ORM\EntityManager;

class EntryMailQuery
{
    /** @var  EntityManager */
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Find all entries that have an empty wishlist in Pools which were sent out
     * more than two weeks ago and the party date is max six weeks in the future.
     *
     * @return array
     */
    public function findAllToRemindOfEmptyWishlist()
    {
        $today = new \DateTime();
        $twoWeeksAgo = new \DateTime('now - 2 weeks');
        $sixWeeksFromNow = new \DateTime('now + 6 weeks');

        $query = $this->em->createQuery('
            SELECT entry
            FROM IntractoSecretSantaBundle:Entry entry
              JOIN entry.pool pool
            WHERE entry.wishlist_updated = 0
              AND pool.created = 1
              AND pool.eventdate > :today
              AND pool.eventdate < :sixWeeksFromNow
              AND pool.sentdate < :twoWeeksAgo
              AND (entry.emptyWishlistReminderSentTime IS NULL OR entry.emptyWishlistReminderSentTime < :twoWeeksAgo)
        ');

        $query->setParameter('today', $today, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('twoWeeksAgo', $twoWeeksAgo, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('sixWeeksFromNow', $sixWeeksFromNow, \Doctrine\DBAL\Types\Type::DATETIME);

        return $query->getResult();
    }

    /**
     * Find all entries that haven't been watched yet in Pools which were sent
     * out more than two weeks ago and the party date is max six weeks in the
     * future.
     *
     * @return array
     */
    public function findAllToRemindToViewEntry()
    {
        $today = new \DateTime();
        $oneWeekAgo = new \DateTime('now - 1 week');
        $twoWeeksAgo = new \DateTime('now - 2 weeks');
        $sixWeeksFromNow = new \DateTime('now + 6 weeks');

        $query = $this->em->createQuery('
            SELECT entry
            FROM IntractoSecretSantaBundle:Entry entry
              JOIN entry.pool pool
            WHERE entry.viewdate IS NULL
              AND pool.created = 1
              AND pool.eventdate > :today
              AND pool.eventdate < :sixWeeksFromNow
              AND pool.sentdate < :twoWeeksAgo
              AND (entry.viewReminderSentTime IS NULL OR entry.viewReminderSentTime < :oneWeekAgo)
        ');

        $query->setParameter('today', $today, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('oneWeekAgo', $oneWeekAgo, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('twoWeeksAgo', $twoWeeksAgo, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('sixWeeksFromNow', $sixWeeksFromNow, \Doctrine\DBAL\Types\Type::DATETIME);

        return $query->getResult();
    }

    /**
     * @return array
     */
    public function findAllToRemindOfUpdatedWishlist()
    {
        $today = new \DateTime();
        $oneDayAgo = new \DateTime('now - 1 day');
        $twoWeeksAgo = new \DateTime('now - 2 weeks');
        $sixWeeksFromNow = new \DateTime('now + 6 weeks');

        $query = $this->em->createQuery('
            SELECT entry
            FROM IntractoSecretSantaBundle:Entry entry
              JOIN entry.pool pool
              JOIN entry.entry peer
            WHERE peer.wishlist_updated = 1
              AND pool.created = 1
              AND pool.eventdate > :today
              AND pool.eventdate < :sixWeeksFromNow
              AND pool.sentdate < :twoWeeksAgo
              AND (entry.updateWishlistReminderSentTime < :oneDayAgo OR entry.updateWishlistReminderSentTime IS NULL)
        ');

        $query->setParameter('today', $today, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('oneDayAgo', $oneDayAgo, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('twoWeeksAgo', $twoWeeksAgo, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('sixWeeksFromNow', $sixWeeksFromNow, \Doctrine\DBAL\Types\Type::DATETIME);

        return $query->getResult();
    }

    /**
     * @return array
     */
    public function findAllAdminsForPoolStatusMail()
    {
        $today = new \DateTime();
        $oneWeekAgo = new \DateTime('now - 1 week');
        $twoWeeksAgo = new \DateTime('now - 2 weeks');
        $sixWeeksFromNow = new \DateTime('now + 6 weeks');

        $query = $this->em->createQuery('
            SELECT entry
            FROM IntractoSecretSantaBundle:Entry entry
              JOIN entry.pool pool
            WHERE entry.poolAdmin = 1
              AND entry.url IS NOT NULL
              AND pool.created = 1
              AND pool.eventdate > :today
              AND pool.eventdate < :sixWeeksFromNow
              AND pool.sentdate < :twoWeeksAgo
              AND (entry.poolStatusSentTime < :oneWeekAgo OR entry.poolStatusSentTime IS NULL)
        ');

        $query->setParameter('today', $today, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('oneWeekAgo', $oneWeekAgo, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('twoWeeksAgo', $twoWeeksAgo, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('sixWeeksFromNow', $sixWeeksFromNow, \Doctrine\DBAL\Types\Type::DATETIME);

        return $query->getResult();
    }
}
