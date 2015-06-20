<?php

namespace Intracto\SecretSantaBundle\Entity;

use Doctrine\ORM\EntityRepository;

class EntryRepository extends EntityRepository
{
    public function findAfter(\DateTime $startDate)
    {
        $query = $this->_em->createQuery("
            SELECT entry
            FROM IntractoSecretSantaBundle:Entry entry
            JOIN entry.pool pool
            JOIN entry.entry peer
            WHERE pool.sentdate >= :startDate
              AND peer.wishlist IS NOT NULL
        ");
        $query->setParameter('startDate', $startDate, \Doctrine\DBAL\Types\Type::DATETIME);

        return $query->getResult();
    }

    public function findAllForWishlistNofifcication()
    {
        $query = $this->_em->createQuery("
            SELECT entry
            FROM IntractoSecretSantaBundle:Entry entry
            JOIN entry.entry peer
            WHERE peer.wishlist_updated = 1
        ");

        return $query->getResult();
    }

    /**
     * Find all entries that haven't been watched yet in Pools which were sent
     * out more than two weeks ago and the party date is still in the future.
     *
     * @return Entry[]
     */
    public function findAllToRemindToViewEntry()
    {
        return [];
    }

    /**
     * Find all entries that havean empty wishlit in Pools which were sent
     * out more than two weeks ago and the party date is still in the future.
     *
     * @return Entry[]
     */
    public function findAllToRemindOfEmptyWishlist()
    {
        return [];
    }

}
