<?php

namespace Intracto\SecretSantaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WishlistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('wishlist')
          ->add('Update my wishlist', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Intracto\SecretSantaBundle\Entity\Entry'
            )
        );
    }

    public function getName()
    {
        return 'intracto_secretsantabundle_wishlisttype';
    }
}
