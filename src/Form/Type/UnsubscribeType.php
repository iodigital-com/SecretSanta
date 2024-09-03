<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class UnsubscribeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
	{
        $builder
            ->add('allParties', CheckboxType::class, [
                'label' => 'participant_unsubscribe.unsubscribe_all_label',
                'required' => false,
            ])
            ->add('blacklist', CheckboxType::class, [
                'label' => 'participant_unsubscribe.unsubscribe_blacklist',
                'required' => false,
            ]);
    }
}
