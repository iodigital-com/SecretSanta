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

    public function validate($party, Constraint $constraint)
    {
        if (!$this->participantShuffler->shuffleParticipants($party)) {
            $this->context->buildViolation($constraint->message)
                ->atPath('entries')
                ->addViolation();
        }
    }
}
