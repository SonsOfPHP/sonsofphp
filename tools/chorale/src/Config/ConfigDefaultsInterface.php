<?php

declare(strict_types=1);

namespace Chorale\Config;

interface ConfigDefaultsInterface
{
    /**
     * @param array<string, mixed> $config Raw parsed YAML or empty array.
     * @return array{
     *   repo_host: string,
     *   repo_vendor: string,
     *   repo_name_template: string,
     *   default_repo_template: string,
     *   default_branch: string,
     *   splitter: string,
     *   tag_strategy: string,
     *   rules: array<string, mixed>
     * }
     */
    public function resolve(array $config): array;
}
