<?php

namespace Intracto\SecretSantaBundle\Form\Type;

use Intracto\SecretSantaBundle\Entity\Party;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message', TextareaType::class)
            ->add('participants', CollectionType::class, [
                'entry_type' => ParticipantType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('eventdate', DateType::class, [
                'label' => 'form-pool.label.date_party',
            ])
            ->add('amount', TextType::class, [
                'label' => 'form-pool.label.amount_to_spend',
            ])
            ->add('location', TextType::class, [
                'label' => 'form-pool.label.location',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Party::class,
            'action' => '#mysanta',
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ]);
    }
}
