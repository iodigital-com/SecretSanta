<?php

namespace Intracto\SecretSantaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdatePoolDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'eventdate',
                'genemu_jquerydate',
                [
                    'widget' => 'single_text',
                    'label' => 'label.date_party',
                    'format' => 'dd-MM-yyyy',
                    'configs' => [
                        'minDate' => 0,
                    ],
                ]
            )
            ->add(
                'amount',
                'text',
                [
                    'label' => 'label.amount_to_spend',
                ]
            )
            ->add(
                'location',
                'text',
                [
                    'label' => 'label.location',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'Intracto\SecretSantaBundle\Entity\Pool',
            ]
        );
    }

    public function getName()
    {
        return 'intracto_secretsantabundle_updatepooldetailstype';
    }
}
