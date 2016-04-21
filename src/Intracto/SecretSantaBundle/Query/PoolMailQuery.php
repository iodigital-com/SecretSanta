<?php

namespace Intracto\SecretSantaBundle\Query;

use Doctrine\ORM\EntityManager;

class PoolMailQuery
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
     * @return mixed
     */
    public function findAllToNotifyOfUpdatedPartyMail()
    {
        $today = new \DateTime();
        $oneHourAgo = new \DateTime('now - 1 hour');
        $twoWeeksAgo = new \DateTime('now - 2 weeks');
        $sixWeeksFromNow = new \DateTime('now + 6 weeks');

        $query = $this->em->createQuery('
            SELECT pool
            FROM IntractoSecretSantaBundle:Pool pool
            WHERE pool.detailsUpdated = 1
              AND pool.created = 1
              AND pool.eventdate > :today
              AND pool.eventdate < :sixWeeksFromNow
              AND pool.sentdate < :twoWeeksAgo
              AND pool.detailsUpdatedTime < :oneHourAgo
        ');

        $query->setParameter('today', $today, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('oneHourAgo', $oneHourAgo, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('twoWeeksAgo', $twoWeeksAgo, \Doctrine\DBAL\Types\Type::DATETIME);
        $query->setParameter('sixWeeksFromNow', $sixWeeksFromNow, \Doctrine\DBAL\Types\Type::DATETIME);

        return $query->getResult();
    }
}
