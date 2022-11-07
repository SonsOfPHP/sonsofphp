<?php

declare(strict_types=1);

namespace SonsOfPHP\Bundle\Cqrs;

use SonsOfPHP\Bundle\Cqrs\DependencyInjection\CqrsExtension;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

/**
 * @experimental
 */
final class CqrsBundle extends AbstractBundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        // @todo
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new CqrsExtension();
    }
}
