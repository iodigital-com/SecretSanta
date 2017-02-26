<?php

namespace Intracto\SecretSantaBundle\Twig\Extension;

use Symfony\Component\Form\FormView;
use Symfony\Bridge\Twig\Form\TwigRendererInterface;

class FormExtension extends \Twig_Extension
{
    /**
     * This property is public so that it can be accessed directly from compiled
     * templates without having to call a getter, which slightly decreases performance.
     *
     * @var \Symfony\Component\Form\FormRendererInterface
     */
    public $renderer;

    /**
     * @param TwigRendererInterface $renderer
     */
    public function __construct(TwigRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('form_javascript', array($this, 'renderJavascript'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('form_stylesheet', null, array(
                'is_safe' => array('html'),
                'node_class' => 'Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode',
            )),
        );
    }

    /**
     * Render Function Form Javascript
     *
     * @param FormView $view
     * @param bool     $prototype
     *
     * @return string
     */
    public function renderJavascript(FormView $view, $prototype = false)
    {
        $block = $prototype ? 'javascript_prototype' : 'javascript';

        return $this->renderer->searchAndRenderBlock($view, $block);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'intracto_secret_santa.twig.extension.form';
    }
}
