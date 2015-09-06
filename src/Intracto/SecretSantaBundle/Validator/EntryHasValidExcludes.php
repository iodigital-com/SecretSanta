<?php

namespace Intracto\SecretSantaBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class EntryHasValidExcludes extends Constraint
{
    public $messageNoUniqueMatch = 'entry.non_unique';

    public function validatedBy()
    {
        return 'intracto.validator.entry_has_valid_excludes';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
