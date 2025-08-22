<?php

declare(strict_types=1);

namespace Chorale\Config;

use Chorale\IO\BackupManagerInterface;
use Symfony\Component\Yaml\Yaml;

final readonly class ConfigWriter implements ConfigWriterInterface
{
    public function __construct(
        private BackupManagerInterface $backup,
        private string $fileName = 'chorale.yaml'
    ) {}

    public function write(string $projectRoot, array $config): void
    {
        $path = rtrim($projectRoot, '/') . '/' . $this->fileName;

        // backup first
        $this->backup->backup($path);

        $yaml = Yaml::dump($config, 8, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK);
        $tmp  = $path . '.tmp';

        if (@file_put_contents($tmp, $yaml) === false) {
            throw new \RuntimeException('Failed to write temp file: ' . $tmp);
        }

        if (!@rename($tmp, $path)) {
            @unlink($tmp);
            throw new \RuntimeException('Failed to replace ' . $path);
        }
    }
}
