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
    public function shuffleEntries($pool){

        // Get all entries for this pool
        $entries = $pool->getEntries();

        // Create array with ids for each entry
        $entry_ids = array();
        foreach($entries as $e){
            $entry_ids[] = $e->getId();
        }
        
        // Shuffle array
        shuffle($entry_ids);
        
        foreach($entry_ids as $key => $entry_id){

            // Define secret santa id
            if($key != 0){
                // Get previous entry
                $secret_santa_entry_id = $entry_ids[$key - 1];    
            }else{
                // Get last entry
                $secret_santa_entry_id = $entry_ids[count($entry_ids) - 1];
            }

            // Generate url here (Since we're doing an update query anyway)
            $url = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);

            // Update entry
            $qb = $this->getEntityManager()->createQueryBuilder();
            $q = $qb->update('\Intracto\SecretSantaBundle\Entity\Entry', 'e')
                ->set('e.entry', '?1')
                ->set('e.url', '?3')
                ->where('e.id = ?2')
                ->setParameter(1, $secret_santa_entry_id)
                ->setParameter(2, $entry_id)
                ->setParameter(3, $url)
                ->getQuery();
            $p = $q->execute();
        }
    }

    /**
    * Sends out all mails for a Pool
    *
    * @param object $pool
    * @return boolean
    */
    public function sendSecretSantaMailsForPool($pool){

        $pool->setSentdate(new \DateTime("now"));

        $em = $this->getEntityManager();
        $em->persist($pool);
        $em->flush();

        foreach($pool->getEntries() as $entry){
            $entry->sendSecretSantaMail();
        }

    }


}