<?php

namespace Intracto\SecretSantaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WishlistItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rank', 'hidden')
            ->add('description', 'text')
            ->add('image');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Intracto\SecretSantaBundle\Entity\WishlistItem',
            )
        );
    }

    public function getName()
    {
        return 'intracto_secretsantabundle_wishlistitemtype';
    }
}
