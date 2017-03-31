<?php

namespace Intracto\SecretSantaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class UnsubscribeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('allParties', CheckboxType::class, [
                'label' => 'participant_unsubscribe.unsubscribe_all_label',
                'required' => false,
            ]);
    }
}
