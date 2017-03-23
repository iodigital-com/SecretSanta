<?php

namespace Intracto\SecretSantaBundle\Validator;

use Intracto\SecretSantaBundle\Entity\Participant;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EntryHasValidExcludesValidator extends ConstraintValidator
{
    /**
     * @param Participant $participant
     * @param Constraint  $constraint
     */
    public function validate($participant, Constraint $constraint)
    {
        $party = $participant->getParty();
        //should be at least 2 possible entries remaining to choose from, -1 for itself
        if ($party->getParticipants()->count() < $participant->getExcludedParticipants()->count() + 3) {
            $this->context->addViolationAt(
                'excluded_participants',
                $constraint->messageNoUniqueMatch,
                ['%name%' => $participant->getName()]
            );
        }
        //Should not be necessary but you never know eyy..
        if ($participant->getExcludedParticipants()->contains($participant)) {
            $this->context->addViolationAt(
                'excluded_participants',
                '%name% can not exclude itself',
                ['%name%' => $participant->getName()]
            );
        }
    }
}
