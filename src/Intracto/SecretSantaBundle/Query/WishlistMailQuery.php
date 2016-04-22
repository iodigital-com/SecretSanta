<?php

namespace Intracto\SecretSantaBundle\Query;

use Doctrine\DBAL\Connection;
use Intracto\SecretSantaBundle\Entity\Entry;

class WishlistMailQuery
{
    /** @var Connection */
    private $dbal;

    public function __construct(Connection $dbal)
    {
        $this->dbal = $dbal;
    }

    /**
     * @param Entry $entry
     */
    public function countWishlistItemsOfParticipant(Entry $entry)
    {
        $query = $this->dbal->createQueryBuilder()
            ->select('count(w.id) AS wishlistItemCount')
            ->from('WishlistItem', 'w')
            ->where('w.entry_id = :entryId')
            ->setParameter('entryId', $entry->getId());

        return $query->execute()->fetchAll();
    }
}
