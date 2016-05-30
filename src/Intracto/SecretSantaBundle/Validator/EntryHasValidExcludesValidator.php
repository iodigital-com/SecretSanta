<?php

namespace Intracto\SecretSantaBundle\Validator;

use Intracto\SecretSantaBundle\Entity\Entry;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EntryHasValidExcludesValidator extends ConstraintValidator
{
    public function validate($entry, Constraint $constraint)
    {
        /*
         * @var Entry $entry
         */
        $pool = $entry->getPool();
        //should be at least 2 possible entries remaining to choose from, -1 for itself
        if ($pool->getEntries()->count() < $entry->getExcludedEntries()->count() + 3) {
            $this->context->addViolationAt(
                'excluded_entries',
                $constraint->messageNoUniqueMatch,
                ['%name%' => $entry->getName()]
            );
        }
        //Should not be necessary but you never know eyy..
        if ($entry->getExcludedEntries()->contains($entry)) {
            $this->context->addViolationAt(
                'excluded_entries',
                '%name% can not exclude itself',
                ['%name%' => $entry->getName()]
            );
        }
    }
}
