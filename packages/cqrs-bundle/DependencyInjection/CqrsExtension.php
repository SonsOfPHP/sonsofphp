<?php

declare(strict_types=1);

namespace SonsOfPHP\Bundle\Cqrs\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @experimental
 */
class CqrsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);
    }
}
