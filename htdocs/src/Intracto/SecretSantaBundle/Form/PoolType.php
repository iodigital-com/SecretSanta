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
        $initial_message = "Join the Secret Santa fun and find out who your gift buddy is by clicking the button below.\n\nYou can spend up to 15 EUR for your gift. But of course creating your own present is allowed. Even encouraged!\nThe Secret Santa party is planned January 8th. Be sure to bring your gift!\n\nMerry Christmas!";
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
