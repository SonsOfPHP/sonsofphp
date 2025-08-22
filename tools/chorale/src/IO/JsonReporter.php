<?php

declare(strict_types=1);

namespace Chorale\IO;

final class JsonReporter implements JsonReporterInterface
{
    public function build(array $defaults, array $discoverySets, array $actions): string
    {
        $payload = [
            'defaults'  => $defaults,
            'discovery' => $discoverySets,
            'plan'      => ['actions' => $actions],
        ];

        $json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            throw new \RuntimeException('Failed to encode JSON output.');
        }
        return $json . PHP_EOL;
    }
}
