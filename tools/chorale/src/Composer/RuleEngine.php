<?php

declare(strict_types=1);

namespace Chorale\Composer;

/**
 * Skeleton rule engine that produces no edits.
 * Implement rules: mirror, mirror-unless-overridden, merge-object, append-unique, ignore.
 */
final class RuleEngine implements RuleEngineInterface
{
    public function computePackageEdits(array $packageComposer, array $rootComposer, array $config, array $context): array
    {
        // TODO: Apply precedence target > pattern > root rule > package
        // TODO: Respect composer_sync.rules defaults and composer_overrides in patterns/targets
        // TODO: Support templating in values (homepage, etc.)
        return [];
    }
}
