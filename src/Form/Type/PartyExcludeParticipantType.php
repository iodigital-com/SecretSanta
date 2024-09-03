<?php

namespace App\Form\Type;

use App\Entity\Party;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class PartyExcludeParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
	{
        $builder
            ->add(
                'participants',
                CollectionType::class,
                [
                    'entry_type' => ExcludeParticipantType::class,
                    'by_reference' => false,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
	{
        $resolver->setDefaults(
            [
                'data_class' => Party::class,
                'validation_groups' => ['exclude_participants'],
                'constraints' => [
                    new Valid(),
                ],
            ]
        );
    }
}
