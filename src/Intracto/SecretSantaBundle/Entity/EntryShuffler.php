<?php

namespace Intracto\SecretSantaBundle\Entity;

/**
 * Class EntryShuffler
 */
class EntryShuffler
{
    /**
     * @param Pool $pool
     * @return array|bool
     */
    public function shuffleEntries(Pool $pool)
    {
        return $this->permutateTillMatch($pool);
    }

    /**
     * @param Pool $pool
     * @return array|bool
     */
    private function permutateTillMatch(Pool $pool)
    {
        $entries = $pool->getEntries()->getValues();
        $set = $this->shuffleArray($entries);
        $size = count($set) - 1;
        $perm = range(0, $size);
        do {
            $shuffled = array();
            foreach ($perm as $i) {
                $shuffled[] = $set[$i];
            }

            if ($this->checkValidMatch($entries, $shuffled)) {
                return $shuffled;
            }
        } while ($perm = $this->nextPermutation($perm, $size));

        return false;
    }

    /**
     * @param $entries
     * @param $shuffled
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
     * @return mixed
     */
    private function shuffleArray($list)
    {
        shuffle($list);

        return $list;
    }

    /**
     * Credits to: http://docstore.mik.ua/orelly/webprog/pcook/ch04_26.htm
     *
     * @param $p
     * @param $size
     * @return bool
     */
    private function nextPermutation($p, $size)
    {
        // slide down the array looking for where we're smaller than the next guy
        for ($i = $size - 1; array_key_exists($i, $p) && $p[$i] >= $p[$i + 1]; --$i) {
        }

        // if this doesn't occur, we've finished our permutations
        // the array is reversed: (1, 2, 3, 4) => (4, 3, 2, 1)
        if ($i == -1) {
            return false;
        }

        // slide down the array looking for a bigger number than what we found before
        for ($j = $size; $p[$j] <= $p[$i]; --$j) {
        }

        // swap them
        $tmp = $p[$i];
        $p[$i] = $p[$j];
        $p[$j] = $tmp;

        // now reverse the elements in between by swapping the ends
        for (++$i, $j = $size; $i < $j; ++$i, --$j) {
            $tmp = $p[$i];
            $p[$i] = $p[$j];
            $p[$j] = $tmp;
        }

        return $p;
    }
}
