<?php

namespace Intracto\SecretSantaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PoolExcludeEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'entries',
                'collection',
                array(
                    'type' => new ExcludeEntryType(),
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Intracto\SecretSantaBundle\Entity\Pool',
                'validation_groups' => array('exclude_entries'),
                'cascade_validation' => true,
            )
        );
    }

    public function getName()
    {
        return 'intracto_secretsantabundle_poolexcludeentrytype';
    }
}
