<?php

declare(strict_types=1);

namespace Chorale\Config;

use Symfony\Component\Yaml\Yaml;

final class ConfigLoader implements ConfigLoaderInterface
{
    public function __construct(
        private readonly string $fileName = 'chorale.yaml'
    ) {}

    public function load(string $projectRoot): array
    {
        $path = rtrim($projectRoot, '/') . '/' . $this->fileName;
        if (!is_file($path)) {
            return [];
        }
        $raw = file_get_contents($path);
        if ($raw === false) {
            throw new \RuntimeException("Failed to read {$path}");
        }
        $data = Yaml::parse($raw);
        return is_array($data) ? $data : [];
    }
}
