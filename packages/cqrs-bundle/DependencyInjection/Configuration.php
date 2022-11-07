<?php

declare(strict_types=1);

namespace SonsOfPHP\Bundle\Cqrs\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @experimental
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder('sonsofphp');

        return $builder;
    }
}
