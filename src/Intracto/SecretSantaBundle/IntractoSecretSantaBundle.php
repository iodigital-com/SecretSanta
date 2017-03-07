<?php

namespace Intracto\SecretSantaBundle;

use Intracto\SecretSantaBundle\DependencyInjection\Compiler\FormCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class IntractoSecretSantaBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new FormCompilerPass());
    }
}
