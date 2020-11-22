<?php

namespace App\Tests\Behat;

use Symfony\Component\DependencyInjection\ContainerInterface;

trait ContainerAwareContextTrait
{
    /**
     * @var ContainerInterface
     */
    protected ?ContainerInterface $container;

    /**
     * @required
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    protected function getContainer(): ?ContainerInterface
    {
        return $this->container;
    }
}
