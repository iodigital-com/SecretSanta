<?php

namespace Intracto\SecretSantaBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ExcludeEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $me = $event->getData();
            $form = $event->getForm();

            $form->add('excluded_entries', 'entity', array(
                'class' => 'IntractoSecretSantaBundle:Entry',
                'multiple' => true,
                'expanded' => false,
                'property' => 'name',
                'label' => $me->getName(),
                'attr' => array('data-entry' => $me->getId()),
                'query_builder' => function (EntityRepository $er) use ($me) {
                    return $er->createQueryBuilder('e')
                        ->where('e.pool = :pool')
                        ->andWhere('e != :me')
                        ->setParameters(array(
                            'pool' => $me->getPool(),
                            'me' => $me
                        ));
                },
            ));
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Intracto\SecretSantaBundle\Entity\Entry'
        ));
    }

    public function getName()
    {
        return 'intracto_secretsantabundle_excludeentrytype';
    }
}
