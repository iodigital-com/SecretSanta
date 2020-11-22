<?php

namespace App\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * FormCompilerPass.
 *
 * Adds new twig.form.resources
 */
class FormCompilerPass implements CompilerPassInterface
{
    /**
     * @var array
     */
    private $templates = ['jquery'];

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $resources = $container->getParameter('twig.form.resources');

        foreach ($this->templates as $template) {
            $resources[] = '/Form/'.$template.'_layout.html.twig';
        }

        $container->setParameter('twig.form.resources', $resources);
    }
}
