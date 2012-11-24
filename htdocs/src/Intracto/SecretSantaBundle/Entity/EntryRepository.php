<?php

namespace Intracto\SecretSantaBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * EntryRepository
 *
 */
class EntryRepository extends EntityRepository
{

    /**
    * Shuffles all entries for pool and save result to each entry
    *
    * @param object $pool
    * @return boolean
    */
    public function shuffleEntries($pool)
    {
        // Get all entries for this pool
        $entries = $pool->getEntries();

        // Create array with ids for each entry
        $entry_ids = array();
        foreach($entries as $e){
            $entry_ids[] = $e->getId();
        }
        
        // Shuffle array
        shuffle($entry_ids);
        
        // Safe result to each entry
        foreach($entry_ids as $key => $entry_id){

            if($key != 0){
                // Get previous entry
            $secret_santa_entry_id = $entry_ids[$key - 1];    
            }else{
                // Get last entry
                $secret_santa_entry_id = $entry_ids[count($entry_ids) - 1];
            }

            $qb = $this->getEntityManager()->createQueryBuilder();
            $q = $qb->update('\Intracto\SecretSantaBundle\Entity\Entry', 'e')
                ->set('e.entry', '?1')
                ->where('e.id = ?2')
                ->setParameter(1, $secret_santa_entry_id)
                ->setParameter(2, $entry_id)
                ->getQuery();
            $p = $q->execute();
        }
    }


}