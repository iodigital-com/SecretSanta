<?php

namespace Intracto\SecretSantaBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PoolHasValidExcludes extends Constraint
{
    public $message = 'Not all entries have an unique match.';

    public function validatedBy()
    {
        return 'intracto.validator.pool_has_valid_excludes';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}

