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
        return 'intracto_secret_santa.validator.participant_is_not_blacklisted';
    }
}
