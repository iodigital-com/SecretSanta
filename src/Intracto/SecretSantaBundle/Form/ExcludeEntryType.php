<?php

namespace Intracto\SecretSantaBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use JMS\TranslationBundle\Annotation\Ignore;

class ExcludeEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $me = $event->getData();
            $form = $event->getForm();

            $form->add('excluded_entries', EntityType::class, array(
                'class' => 'IntractoSecretSantaBundle:Entry',
                'multiple' => true,
                'expanded' => false,
                'property' => 'name',

                /** @Ignore */
                'label' => $me->getName(),
                'attr' => array('data-entry' => $me->getId()),
                'query_builder' => function (EntityRepository $er) use ($me) {
                    return $er->createQueryBuilder('e')
                        ->where('e.pool = :pool')
                        ->andWhere('e != :me')
                        ->setParameters(array(
                            'pool' => $me->getPool(),
                            'me' => $me,
                        ));
                },
                'required' => false,
            ));

        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Intracto\SecretSantaBundle\Entity\Entry',
        ));
    }
}
