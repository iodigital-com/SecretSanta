<?php

namespace Intracto\SecretSantaBundle\Validator;

use Intracto\SecretSantaBundle\Entity\ParticipantShuffler;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PartyHasValidExcludesValidator extends ConstraintValidator
{
    private $participantShuffler;

    // Todo: find a way to activate this validator only if EntryHasValidExcludes passes validation.
    public function __construct(ParticipantShuffler $participantShuffler)
    {
        $this->participantShuffler = $participantShuffler;
    }

    public function validate($pool, Constraint $constraint)
    {
        if (!$this->participantShuffler->shuffleParticipants($pool)) {
            $this->context->addViolationAt(
                'entries',
                $constraint->message
            );
        }
    }
}
