<?php

namespace Intracto\SecretSantaBundle\Entity;

use Doctrine\ORM\EntityManager;

class ParticipantService
{
    /**
     * @var EntityManager
     */
    public $em;

    /**
     * @var ParticipantShuffler
     */
    public $participantShuffler;

    /**
     * @param EntityManager       $em
     * @param ParticipantShuffler $participantShuffler
     */
    public function __construct(EntityManager $em, ParticipantShuffler $participantShuffler)
    {
        $this->em = $em;
        $this->participantShuffler = $participantShuffler;
    }

    /**
     * Shuffles all participants for party and save result to each participant.
     *
     * @param Party $party
     *
     * @return bool
     */
    public function shuffleParticipants(Party $party)
    {
        // Validator should already have shuffled it.
        if (!$shuffled = $this->participantShuffler->shuffleParticipants($party)) {
            return false;
        }

        foreach ($party->getParticipants() as $key => $participant) {
            $match = $shuffled[$key];
            $participant->setAssignedParticipant($match)
                ->setUrl(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));

            $this->em->persist($participant);
        }

        $this->em->flush();
    }
}
