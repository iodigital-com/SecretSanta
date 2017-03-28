<?php

namespace Intracto\SecretSantaBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ParticipantHasValidExcludes extends Constraint
{
    public $messageNoUniqueMatch = 'entry.non_unique';

    public function validatedBy()
    {
        return 'intracto_secret_santa.validator.participant_has_valid_excludes';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
