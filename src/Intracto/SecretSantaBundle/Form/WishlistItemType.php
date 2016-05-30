<?php

namespace Intracto\SecretSantaBundle\Form;

use Intracto\SecretSantaBundle\Entity\WishlistItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WishlistItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rank', HiddenType::class)
            ->add('description', TextType::class)
            ->add('image');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => WishlistItem::class,
            ]
        );
    }
}
