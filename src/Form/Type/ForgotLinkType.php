<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;

class ForgotLinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
	{
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Email([
                        'message' => "The email '{{ value }}' is not a valid email.",
                        'mode' => Email::VALIDATION_MODE_STRICT,
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-large btn-primary btn-create-event'],
                'label' => 'party-forgot_link.submit_btn',
            ])
        ;
    }
}
