<?php

declare(strict_types=1);

namespace Chorale\Config;

interface SchemaValidatorInterface
{
    /**
     * Validate config under an explicit schema path.
     *
     * @param array<string, mixed> $config
     * @param string               $schemaPath absolute or repo-relative path
     * @return list<string> messages; empty means valid
     */
    public function validate(array $config, string $schemaPath): array;
}
