<?php

declare(strict_types=1);

namespace SonsOfPHP\Bundle\Cqrs;

use SonsOfPHP\Bundle\Cqrs\DependencyInjection\CqrsExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

/**
 * @experimental
 */
final class CqrsBundle extends AbstractBundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        // @todo
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new CqrsExtension();
    }
}
