<?php

namespace Intracto\SecretSantaBundle\Form;

use Symfony\Component\Form\AbstractType;
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
                  'label' => 'label.date_party',
              )
          )
          ->add(
              'amount',
              'text',
              array(
                  'label' => 'label.amount_to_spend',
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

    public function getName()
    {
        return 'intracto_secretsantabundle_pooltype';
    }
}
