<?php

namespace Intracto\SecretSantaBundle\Entity;

/**
 * Class EntryShuffler.
 */
class EntryShuffler
{
    const SHUFFLE_TIME_LIMIT = 10; //sec

    private $matchedExcludes;

    /**
     * @param Pool $pool
     *
     * @return array|bool
     */
    public function shuffleEntries(Pool $pool)
    {
        if ($this->matchedExcludes) {
            return $this->matchedExcludes;
        }

        return $this->shuffleTillMatch($pool);
    }

    /**
     * @param Pool $pool
     *
     * @return array|bool
     */
    private function shuffleTillMatch(Pool $pool)
    {
        $timeToStop = microtime(true) + self::SHUFFLE_TIME_LIMIT;
        $entries = $pool->getEntries()->getValues();

        while (microtime(true) < $timeToStop) {
            $set = $this->shuffleArray($entries);
            if ($this->checkValidMatch($entries, $set)) {
                $this->matchedExcludes = $set;

                return $set;
            }
        };

        return false;
    }

    /**
     * @param $entries
     * @param $shuffled
     *
     * @return bool
     */
    private function checkValidMatch($entries, $shuffled)
    {
        foreach ($entries as $key => $entry) {
            $possibleMatch = $shuffled[$key];
            if ($entry->getExcludedEntries()->contains($possibleMatch) || $entry == $possibleMatch) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $list
     *
     * @return mixed
     */
    private function shuffleArray($list)
    {
        shuffle($list);

        return $list;
    }
}
