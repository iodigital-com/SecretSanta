<?php

namespace Intracto\SecretSantaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EmailValidator;

class ForgotLinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array(
                'constraints' => array(
                    new Email(array(
                        'strict' => true,
                        'message' => "The email '{{ value }}' is not a valid email.",
                        'checkMX' => true,
                    ))
                )
            ))
            ->add('submit', SubmitType::class, array(
                'attr' => array('class' => 'btn btn-large btn-primary btn-create-event'),
                'label' => 'manage.resend_email',
            ))
        ;
    }
}
