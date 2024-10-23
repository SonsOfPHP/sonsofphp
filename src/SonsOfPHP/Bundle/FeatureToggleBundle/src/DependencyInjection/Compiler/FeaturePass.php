<?php

declare(strict_types=1);

namespace SonsOfPHP\Bundle\FeatureToggleBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class FeaturePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('sons_of_php.feature_toggle.provider')) {
            return;
        }

        $provider = $container->findDefinition('sons_of_php.feature_toggle.provider');
        $features = $container->findTaggedServiceIds('sons_of_php.feature_toggle.feature');

        foreach (array_keys($features) as $id) {
            $provider->addMethodCall('add', [new Reference($id)]);
        }
    }
}
