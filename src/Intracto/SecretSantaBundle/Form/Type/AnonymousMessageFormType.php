<?php

namespace Intracto\SecretSantaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class AnonymousMessageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
             ->add('message', TextareaType::class, [
                'label' => 'entry_show_valid.anonymous_message.message_label',
                'constraints' => [
                    new NotBlank(),
                ],
                'attr' => [
                    'placeholder' => 'entry_show_valid.anonymous_message.message_placeholder',
                ],
             ])
             ->add('recipient', HiddenType::class)
             ->add('entry', HiddenType::class);
    }
}
