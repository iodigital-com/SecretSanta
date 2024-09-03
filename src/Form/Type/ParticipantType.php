<?php

namespace App\Form\Type;

use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
	{
        $builder
            ->add(
                'name',
                TextType::class,
                ['attr' => ['data-hj-masked' => '']]
            )
            ->add(
                'email',
                TextType::class,
                ['attr' => ['data-hj-masked' => '']]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
	{
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
