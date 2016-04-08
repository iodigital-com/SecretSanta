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
}
