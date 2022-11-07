<?php

declare(strict_types=1);

namespace SonsOfPHP\Bundle\Cqrs\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

/**
 * @experimental
 */
final class CqrsExtension extends Extension
{
    //public function load(array $configs, ContainerBuilder $container)
    //{
    //    $configuration = new Configuration();
    //    $config        = $this->processConfiguration($configuration, $configs);
    //}
}
