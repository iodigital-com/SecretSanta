<?php

namespace App\Query;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Participant;

class ParticipantMailQuery
{
    /** @var EntityManager */
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Find all participants that have an empty wishlist in Parties which were sent out
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

        $query = $this->em->createQuery(sprintf('
            SELECT participant
            FROM %s participant
              JOIN participant.party party
            WHERE participant.wishlistUpdated = 0
              AND party.created = 1
              AND party.eventdate > :today
              AND party.eventdate < :sixWeeksFromNow
              AND party.sentdate < :twoWeeksAgo
              AND (participant.wishlistUpdatedTime IS NULL OR participant.wishlistUpdatedTime < :oneHourAgo)
              AND (participant.emptyWishlistReminderSentTime IS NULL OR participant.emptyWishlistReminderSentTime < :oneWeekAgo)
              AND participant.subscribedForUpdates = 1
        ', Participant::class));

        $query->setParameter('today', $today, Types::DATETIME_MUTABLE);
        $query->setParameter('oneHourAgo', $oneHourAgo, Types::DATETIME_MUTABLE);
        $query->setParameter('oneWeekAgo', $oneWeekAgo, Types::DATETIME_MUTABLE);
        $query->setParameter('twoWeeksAgo', $twoWeeksAgo, Types::DATETIME_MUTABLE);
        $query->setParameter('sixWeeksFromNow', $sixWeeksFromNow, Types::DATETIME_MUTABLE);

        return $query->getResult();
    }

    /**
     * Find all participants that haven't been watched yet in Parties which were sent
     * out more than two weeks ago and the party date is max six weeks in the
     * future.
     *
     * @return Participant[]|array
     */
    public function findAllToRemindToViewParticipant()
    {
        $today = new \DateTime();
        $oneWeekAgo = new \DateTime('now - 1 week');
        $twoWeeksAgo = new \DateTime('now - 2 weeks');
        $sixWeeksFromNow = new \DateTime('now + 6 weeks');

        $query = $this->em->createQuery(sprintf('
            SELECT participant
            FROM %s participant
              JOIN participant.party party
            WHERE participant.viewdate IS NULL
              AND party.created = 1
              AND party.eventdate > :today
              AND party.eventdate < :sixWeeksFromNow
              AND party.sentdate < :twoWeeksAgo
              AND (participant.viewReminderSentTime IS NULL OR participant.viewReminderSentTime < :oneWeekAgo)
              AND participant.subscribedForUpdates = 1
        ', Participant::class));

        $query->setParameter('today', $today, Types::DATETIME_MUTABLE);
        $query->setParameter('oneWeekAgo', $oneWeekAgo, Types::DATETIME_MUTABLE);
        $query->setParameter('twoWeeksAgo', $twoWeeksAgo, Types::DATETIME_MUTABLE);
        $query->setParameter('sixWeeksFromNow', $sixWeeksFromNow, Types::DATETIME_MUTABLE);

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
        $sixWeeksFromNow = new \DateTime('now + 6 weeks');

        $query = $this->em->createQuery(sprintf('
            SELECT participant
            FROM %s participant
              JOIN participant.party party
              JOIN participant.participant peer
            WHERE peer.wishlistUpdated = 1
              AND party.created = 1
              AND party.eventdate > :today
              AND party.eventdate < :sixWeeksFromNow
              AND (participant.wishlistUpdatedTime IS NULL OR participant.wishlistUpdatedTime < :oneHourAgo)
              AND (participant.updateWishlistReminderSentTime IS NULL OR participant.updateWishlistReminderSentTime < :oneDayAgo)
              AND participant.subscribedForUpdates = 1
        ', Participant::class));

        $query->setParameter('today', $today, Types::DATETIME_MUTABLE);
        $query->setParameter('oneHourAgo', $oneHourAgo, Types::DATETIME_MUTABLE);
        $query->setParameter('oneDayAgo', $oneDayAgo, Types::DATETIME_MUTABLE);
        $query->setParameter('sixWeeksFromNow', $sixWeeksFromNow, Types::DATETIME_MUTABLE);

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

        $query = $this->em->createQuery(sprintf('
            SELECT participant
            FROM %s participant
              JOIN participant.party party
            WHERE participant.partyAdmin = 1
              AND participant.url IS NOT NULL
              AND party.created = 1
              AND party.eventdate > :today
              AND party.eventdate < :sixWeeksFromNow
              AND party.sentdate < :twoWeeksAgo
              AND (participant.partyStatusSentTime IS NULL OR participant.partyStatusSentTime < :oneWeekAgo)
              AND participant.subscribedForUpdates = 1
        ', Participant::class));

        $query->setParameter('today', $today, Types::DATETIME_MUTABLE);
        $query->setParameter('oneWeekAgo', $oneWeekAgo, Types::DATETIME_MUTABLE);
        $query->setParameter('twoWeeksAgo', $twoWeeksAgo, Types::DATETIME_MUTABLE);
        $query->setParameter('sixWeeksFromNow', $sixWeeksFromNow, Types::DATETIME_MUTABLE);

        return $query->getResult();
    }
}
