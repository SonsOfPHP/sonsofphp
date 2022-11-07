<?php

declare(strict_types=1);

namespace SonsOfPHP\Bundle\Cqrs\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @experimental
 */
final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder('sonsofphp');

        $builder->getRootNode()
            ->children()
                ->arrayNode('cqrs')
                    ->children()
                        ->booleanNode('enabled')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $builder;
    }
}
