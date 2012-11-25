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
        $initial_message = "Hi there (NAME).\n\nClick on the link bellow to find out your secret santa for our party.\n\nThe maximimum amount of money to spend is 15 EUR, but ofcourse creating your own present is allowed, if not encouraged!\n\n\nSee ya!";
        $builder
            ->add('message', 'textarea', array('data' => $initial_message))
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
