<?php

namespace App\Form\Type;

use App\Entity\Party;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdatePartyDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('eventdate', DateType::class, [
                'label' => 'form-party.label.date_party',
            ])
            ->add('amount', TextType::class, ['label' => 'form-party.label.amount_to_spend'])
            ->add('location', TextType::class, ['label' => 'form-party.label.location'])
        ;

        // We wrap the admin's message into our own message and from 19/apr/2017 we no longer save
        // our own message in the DB. We don't support older parties to prevent the message from occuring twice.
        $party = $builder->getData();
        if ($party->getCreated() || $party->getCreationDate() > new \DateTime('2017-04-20')) {
            $builder->add('message', TextareaType::class, ['label' => 'form-party.label.message']);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Party::class,
        ]);
    }
}
