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
          ->add('message')
          ->add(
              'entries',
              'collection',
              array(
                  'type' => new EntryType(),
                  'allow_add' => true,
                  'allow_delete' => true,
                  'by_reference' => false,
              )
          )
          ->add(
              'date',
              'genemu_jquerydate',
              array(
                  'widget' => 'single_text',
                  'label' => 'Date of your Secret Santa party',
              )
          )
          ->add(
              'amount',
              'text',
              array(
                  'label' => 'Amount to spend',
              )
          );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Intracto\SecretSantaBundle\Entity\Pool'
            )
        );
    }

    public function getName()
    {
        return 'intracto_secretsantabundle_pooltype';
    }
}
