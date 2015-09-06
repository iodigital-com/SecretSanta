<?php

namespace Intracto\SecretSantaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WishlistNewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('wishlistItems', 'collection',
            array(
                'type' => new WishlistItemType(),
                'allow_add' => true,
                'allow_delete' => true,
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Intracto\SecretSantaBundle\Entity\Entry',
            )
        );
    }

    public function getName()
    {
        return 'intracto_secretsantabundle_wishlistnewtype';
    }
}
