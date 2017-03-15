<?php

namespace Intracto\SecretSantaBundle\Form\Extension;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class DateTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'widget' => 'single_text',
            'format' => 'dd-MM-yyyy',
            'append' => '<i class="icon-calendar"></i>',
            'start_date' => 'today',
            'end_date' => '31-12-2100',
            'highlight_currentdate' => true,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        // Adds a custom block prefix
        array_splice(
            $view->vars['block_prefixes'],
            array_search('date', $view->vars['block_prefixes']),
            0,
            'intracto_secret_santa_jquerydatepicker'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['type'] = 'text';

        $view->vars = array_replace($view->vars, array(
            'format' => $options['format'],
            'start_date' => $options['start_date'],
            'end_date' => $options['end_date'],
            'highlight_currentdate' => $options['highlight_currentdate'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return DateType::class;
    }
}
