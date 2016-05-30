<?php

namespace Intracto\SecretSantaBundle\Entity;

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("intracto_secret_santa.entry_service")
 */
class EntryService
{
    /**
     * @DI\Inject("doctrine.orm.entity_manager")
     *
     * @var EntityManager
     */
    public $em;

    /**
     * @DI\Inject("intracto_secret_santa.entry_shuffler")
     *
     * @var EntryShuffler
     */
    public $entryShuffler;

    /**
     * Shuffles all entries for pool and save result to each entry.
     *
     * @param Pool $pool
     *
     * @return bool
     */
    public function shuffleEntries(Pool $pool)
    {
        //Validator should already have shuffled it.
        if (!$shuffled = $this->entryShuffler->shuffleEntries($pool)) {
            return false;
        }

        foreach ($pool->getEntries() as $key => $entry) {
            $match = $shuffled[$key];
            $entry->setEntry($match)
                ->setUrl(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));

            $this->em->persist($entry);
        }

        $this->em->flush();
    }
}
