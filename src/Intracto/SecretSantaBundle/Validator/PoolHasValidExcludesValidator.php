<?php

namespace Intracto\SecretSantaBundle\Validator;

use Intracto\SecretSantaBundle\Entity\EntryShuffler;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PoolHasValidExcludesValidator extends ConstraintValidator
{
    private $entryShuffler;

    // Todo: find a way to activate this validator only if EntryHasValidExcludes passes validation.
    public function __construct(EntryShuffler $entryShuffler)
    {
        $this->entryShuffler = $entryShuffler;
    }

    public function validate($pool, Constraint $constraint)
    {
        if (!$this->entryShuffler->shuffleEntries($pool)) {
            $this->context->addViolationAt(
                'entries',
                $constraint->message
            );
        }
    }
}
