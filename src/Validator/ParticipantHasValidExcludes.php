<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ParticipantHasValidExcludes extends Constraint
{
    public $messageNoUniqueMatch = 'participant.non_unique';

    public function validatedBy(): string
    {
        return PartyHasValidExcludesValidator::class;
    }

    public function getTargets(): array|string
    {
        return self::CLASS_CONSTRAINT;
    }
}
