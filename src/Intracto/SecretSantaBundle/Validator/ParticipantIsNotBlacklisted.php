<?php

namespace Intracto\SecretSantaBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ParticipantIsNotBlacklisted extends Constraint
{
    public function validatedBy()
    {
        return ParticipantIsNotBlacklistedValidator::class;
    }
}
