<?php

namespace Intracto\SecretSantaBundle\Entity;

class ParticipantShuffler
{
    const SHUFFLE_TIME_LIMIT = 10; // seconds

    private $matchedExcludes;

    /**
     * @param Party $party
     *
     * @return array|bool
     */
    public function shuffleParticipants(Party $party)
    {
        if ($this->matchedExcludes) {
            return $this->matchedExcludes;
        }

        return $this->shuffleTillMatch($party);
    }

    /**
     * @param Party $party
     *
     * @return array|bool
     */
    private function shuffleTillMatch(Party $party)
    {
        $timeToStop = microtime(true) + self::SHUFFLE_TIME_LIMIT;
        $participants = $party->getParticipants()->getValues();

        while (microtime(true) < $timeToStop) {
            $set = $this->shuffleArray($participants);
            if ($this->checkValidMatch($participants, $set)) {
                $this->matchedExcludes = $set;

                return $set;
            }
        }

        return false;
    }

    /**
     * @param $participants
     * @param $shuffled
     *
     * @return bool
     */
    private function checkValidMatch($participants, $shuffled)
    {
        foreach ($participants as $key => $participant) {
            $possibleMatch = $shuffled[$key];
            if ($participant === $possibleMatch || $participant->getExcludedParticipants()->contains($possibleMatch)) {
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
