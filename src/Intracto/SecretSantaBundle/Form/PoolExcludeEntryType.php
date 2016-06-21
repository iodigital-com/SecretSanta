<?php

namespace Intracto\SecretSantaBundle\Form;

use Intracto\SecretSantaBundle\Entity\Pool;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PoolExcludeEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'entries',
                CollectionType::class,
                [
                    'entry_type' => ExcludeEntryType::class,
                    'by_reference' => false,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Pool::class,
                'validation_groups' => ['exclude_entries'],
                'cascade_validation' => true,
            ]
        );
    }
}
