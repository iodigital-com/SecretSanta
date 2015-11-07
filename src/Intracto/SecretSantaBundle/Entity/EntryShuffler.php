<?php

namespace Intracto\SecretSantaBundle\Entity;

/**
 * Class EntryShuffler
 */
class EntryShuffler
{
    /**
     * @param Pool $pool
     *
     * @return array|bool
     */
    public function shuffleEntries(Pool $pool)
    {
        return $this->permutateTillMatch($pool);
    }

    /**
     * @param Pool $pool
     *
     * @return array|bool
     */
    private function permutateTillMatch(Pool $pool)
    {
        $entries = $pool->getEntries()->getValues();
        $set = $this->shuffleArray($entries);
        do {
            if ($this->checkValidMatch($entries, $set)) {
                return $set;
            }
        } while ($set = $this->shuffleArray($entries));

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
