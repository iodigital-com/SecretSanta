<?php

namespace Intracto\SecretSantaBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PartyHasValidExcludes extends Constraint
{
    public $message = 'pool.non_unique';

    public function validatedBy()
    {
        return 'intracto.validator.party_has_valid_excludes';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
