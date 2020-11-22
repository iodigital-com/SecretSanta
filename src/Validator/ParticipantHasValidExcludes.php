<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ParticipantHasValidExcludes extends Constraint
{
    public $messageNoUniqueMatch = 'participant.non_unique';

    public function validatedBy()
    {
        return PartyHasValidExcludesValidator::class;
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
