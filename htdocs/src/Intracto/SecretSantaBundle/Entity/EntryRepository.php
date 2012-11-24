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
     *
     * @return boolean
     */
    public function shuffleEntries($pool)
    {
        $entries = $pool->getEntries()->getValues();
        
        shuffle($entries);

        foreach ($entries as $index => $entry) {
            if ($index === count($entries) - 1) {
                $peer = $entries[0];
            } else {
                $peer = $entries[$index + 1];
            }

            $entry
                ->setEntry($peer)
                ->setUrl(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36))
            ;

            $this->_em->persist($entry);
        }
        $this->_em->flush();
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