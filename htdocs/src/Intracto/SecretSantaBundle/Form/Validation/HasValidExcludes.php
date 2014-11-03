<?php


namespace Intracto\SecretSantaBundle\Form\Validation;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class HasValidExcludes extends Constraint
{
    public $message = 'Test "%string%" error message.';

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}

?>