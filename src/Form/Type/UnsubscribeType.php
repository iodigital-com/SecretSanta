<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\FormBuilderInterface;

class UnsubscribeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('unsubscribeOption', ChoiceType::class, [
                'label' => 'participant_unsubscribe.unsubscribe_all_label',
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'participant_unsubscribe.unsubscribe_current_label' => 'current',
                    'participant_unsubscribe.unsubscribe_all_label' => 'all',
                    'participant_unsubscribe.unsubscribe_blacklist' => 'blacklist',
                ],
            ]);
    }
}
