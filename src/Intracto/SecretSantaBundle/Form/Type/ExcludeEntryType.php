<?php

namespace Intracto\SecretSantaBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Intracto\SecretSantaBundle\Entity\Entry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExcludeEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $me = $event->getData();
            $form = $event->getForm();

            $form->add('excluded_entries', EntityType::class, [
                'class' => 'IntractoSecretSantaBundle:Entry',
                'multiple' => true,
                'expanded' => false,
                'choice_label' => 'name',
                'label' => $me->getName(),
                'attr' => ['data-entry' => $me->getId(), 'class' => 'js-selector-entry'],
                'query_builder' => function (EntityRepository $er) use ($me) {
                    return $er->createQueryBuilder('e')
                        ->where('e.pool = :pool')
                        ->andWhere('e != :me')
                        ->setParameters([
                            'pool' => $me->getPool(),
                            'me' => $me,
                        ]);
                },
                'required' => false,
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entry::class,
        ]);
    }
}
