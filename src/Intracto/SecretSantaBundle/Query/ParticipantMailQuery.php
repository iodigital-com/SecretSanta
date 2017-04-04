<?php

namespace Intracto\SecretSantaBundle\Query;

use Doctrine\ORM\EntityManager;
use Intracto\SecretSantaBundle\Entity\Participant;

class ParticipantMailQuery
{
    /** @var EntityManager */
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
     * @return Participant[]|array
     */
    public function findAllToRemindOfEmptyWishlist()
    {
        $today = new \DateTime();
        $oneHourAgo = new \DateTime('now - 1 hour');
        $oneWeekAgo = new \DateTime('now - 1 week');
        $twoWeeksAgo = new \DateTime('now - 2 weeks');
        $sixWeeksFromNow = new \DateTime('now + 6 weeks');

        $query = $this->em->createQuery('
            SELECT participant
            FROM IntractoSecretSantaBundle:Participant participant
              JOIN participant.party party
            WHERE participant.wishlistUpdated = 0
              AND party.created = 1
              AND party.eventdate > :today
              AND party.eventdate < :sixWeeksFromNow
              AND party.sentdate < :twoWeeksAgo
              AND (participant.wishlistUpdatedTime IS NULL OR participant.wishlistUpdatedTime < :oneHourAgo)
              AND (participant.emptyWishlistReminderSentTime IS NULL OR participant.emptyWishlistReminderSentTime < :oneWeekAgo)
              AND participant.isSubscribed = 1
        ');

        $query->setParameter('today', $today, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('oneHourAgo', $oneHourAgo, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('oneWeekAgo', $oneWeekAgo, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('twoWeeksAgo', $twoWeeksAgo, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('sixWeeksFromNow', $sixWeeksFromNow, \Doctrine\DBAL\Types\Type::DATETIME);

        return $query->getResult();
    }

    /**
     * Find all entries that haven't been watched yet in Pools which were sent
     * out more than two weeks ago and the party date is max six weeks in the
     * future.
     *
     * @return Participant[]|array
     */
    public function findAllToRemindToViewEntry()
    {
        $today = new \DateTime();
        $oneWeekAgo = new \DateTime('now - 1 week');
        $twoWeeksAgo = new \DateTime('now - 2 weeks');
        $sixWeeksFromNow = new \DateTime('now + 6 weeks');

        $query = $this->em->createQuery('
            SELECT participant
            FROM IntractoSecretSantaBundle:Participant participant
              JOIN participant.party party
            WHERE participant.viewdate IS NULL
              AND party.created = 1
              AND party.eventdate > :today
              AND party.eventdate < :sixWeeksFromNow
              AND party.sentdate < :twoWeeksAgo
              AND (participant.viewReminderSentTime IS NULL OR participant.viewReminderSentTime < :oneWeekAgo)
              AND participant.isSubscribed = 1
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
        $oneHourAgo = new \DateTime('now - 1 hour');
        $oneDayAgo = new \DateTime('now - 1 day');
        $twoWeeksAgo = new \DateTime('now - 2 weeks');
        $sixWeeksFromNow = new \DateTime('now + 6 weeks');

        $query = $this->em->createQuery('
            SELECT participant
            FROM IntractoSecretSantaBundle:Participant participant
              JOIN participant.party party
              JOIN participant.participant peer
            WHERE peer.wishlistUpdated = 1
              AND party.created = 1
              AND party.eventdate > :today
              AND party.eventdate < :sixWeeksFromNow
              AND party.sentdate < :twoWeeksAgo
              AND (participant.wishlistUpdatedTime IS NULL OR participant.wishlistUpdatedTime < :oneHourAgo)
              AND (participant.updateWishlistReminderSentTime IS NULL OR participant.updateWishlistReminderSentTime < :oneDayAgo)
              AND participant.isSubscribed = 1
        ');

        $query->setParameter('today', $today, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('oneHourAgo', $oneHourAgo, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('oneDayAgo', $oneDayAgo, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('twoWeeksAgo', $twoWeeksAgo, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('sixWeeksFromNow', $sixWeeksFromNow, \Doctrine\DBAL\Types\Type::DATETIME);

        return $query->getResult();
    }

    /**
     * @return array
     */
    public function findAllAdminsForPartyStatusMail()
    {
        $today = new \DateTime();
        $oneWeekAgo = new \DateTime('now - 1 week');
        $twoWeeksAgo = new \DateTime('now - 2 weeks');
        $sixWeeksFromNow = new \DateTime('now + 6 weeks');

        $query = $this->em->createQuery('
            SELECT participant
            FROM IntractoSecretSantaBundle:Participant participant
              JOIN participant.party party
            WHERE participant.partyAdmin = 1
              AND participant.url IS NOT NULL
              AND party.created = 1
              AND party.eventdate > :today
              AND party.eventdate < :sixWeeksFromNow
              AND party.sentdate < :twoWeeksAgo
              AND (participant.partyStatusSentTime IS NULL OR participant.partyStatusSentTime < :oneWeekAgo)
              AND participant.isSubscribed = 1
        ');

        $query->setParameter('today', $today, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('oneWeekAgo', $oneWeekAgo, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('twoWeeksAgo', $twoWeeksAgo, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('sixWeeksFromNow', $sixWeeksFromNow, \Doctrine\DBAL\Types\Type::DATETIME);

        return $query->getResult();
    }
}
