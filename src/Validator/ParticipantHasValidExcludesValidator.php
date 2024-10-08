<?php

namespace App\Validator;

use App\Entity\Participant;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ParticipantHasValidExcludesValidator extends ConstraintValidator
{
    /**
     * @param Participant                 $participant
     * @param ParticipantHasValidExcludes $constraint
     */
    public function validate($participant, Constraint $constraint)
    {
        $party = $participant->getParty();
        // should be at least 2 possible participants remaining to choose from, -1 for itself
        if ($party->getParticipants()->count() < $participant->getExcludedParticipants()->count() + 3) {
            $this->context->buildViolation($constraint->messageNoUniqueMatch)
                ->atPath('exclude_participants')
                ->setParameter('%name%', $participant->getName())
                ->addViolation();
        }
        // Should not be necessary but you never know eyy..
        if ($participant->getExcludedParticipants()->contains($participant)) {
            $this->context->buildViolation('%name% can not exclude itself')
                ->atPath('exclude_participants')
                ->setParameter('%name%', $participant->getName())
                ->addViolation();
        }
    }
}
