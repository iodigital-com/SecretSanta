<?php

namespace Intracto\SecretSantaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Intracto\SecretSantaBundle\Form\EntryType;

class PoolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('listname')
            ->add('message')
            ->add('username')
            ->add('email')
            ->add('entries', 'collection', array(
                'type' => new EntryType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Intracto\SecretSantaBundle\Entity\Pool'
        ));
    }

    public function getName()
    {
        return 'intracto_secretsantabundle_pooltype';
    }
}
