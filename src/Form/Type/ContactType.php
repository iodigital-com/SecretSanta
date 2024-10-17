<?php

namespace App\Form\Type;

use App\Model\ContactSubmission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subject', ChoiceType::class, [
                'choices' => [
                    'form-contact.label.subjects.mail' => 'Mail problem',
                    'form-contact.label.subjects.party' => 'Party problem',
                    'form-contact.label.subjects.bug' => 'Bug reported',
                    'form-contact.label.subjects.gdpr' => 'GDPR issue',
                    'form-contact.label.subjects.other' => 'Other',
                ],
                'label' => 'form-contact.label.subject',
            ])
            ->add('name', TextType::class, [
                'label' => 'form-contact.label.name',
            ])
            ->add('email', EmailType::class, [
                'label' => 'form-contact.label.email',
            ])
            ->add('message', TextareaType::class, [
                'label' => 'form-contact.label.message',
            ])
            ->add('recaptchaToken', HiddenType::class,
                ['attr' => ['class' => 'js-recaptchaToken'],
                ])
            ->add('submit', SubmitType::class, [
                'label' => 'form-contact.label.submit',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactSubmission::class,
        ]);
    }
}
