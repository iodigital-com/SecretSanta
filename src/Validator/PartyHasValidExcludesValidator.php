<?php

namespace App\Validator;

use App\Entity\Party;
use App\Service\ParticipantShuffler;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PartyHasValidExcludesValidator extends ConstraintValidator
{
    // Todo: find a way to activate this validator only if ParticipantHasValidExcludes passes validation.
    public function __construct(private ParticipantShuffler $participantShuffler)
    {}

	/**
	 * @param Party $party
	 * @param PartyHasValidExcludes $constraint
	 */
    public function validate($party, Constraint $constraint): void
	{
        //TODO: workaround for validator being applied to form and field (field == participant entity)
        if (!$party instanceof Party) {
            return;
        }

        if (!$this->participantShuffler->shuffleParticipants($party)) {
            $this->context->buildViolation($constraint->messageNoUniqueMatch)
                ->atPath('participants')
                ->addViolation();
        }
    }
}
