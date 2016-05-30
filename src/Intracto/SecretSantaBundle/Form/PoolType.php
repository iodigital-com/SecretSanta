<?php

namespace Intracto\SecretSantaBundle\Form;

use Genemu\Bundle\FormBundle\Form\JQuery\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PoolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message')
            ->add(
                'entries',
                CollectionType::class,
                array(
                    'entry_type' => EntryType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                )
            )
            ->add(
                'eventdate',
                DateType::class,
                array(
                    'widget' => 'single_text',
                    'label' => 'label.date_party',
                    'format' => 'dd-MM-yyyy',
                    'configs' => [
                        'minDate' => 0,
                    ],
                )
            )
            ->add(
                'amount',
                TextType::class,
                array(
                    'label' => 'label.amount_to_spend',
                )
            )
            ->add(
                'location',
                TextType::class,
                array(
                    'label' => 'label.location',
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Intracto\SecretSantaBundle\Entity\Pool',
            )
        );
    }
}
