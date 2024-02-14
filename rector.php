<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
    ])
    ->withSkip([
        __DIR__ . '/src/SonsOfPHP/*/vendor/*',
        __DIR__ . '/src/SonsOfPHP/*/*/vendor/*',
        __DIR__ . '/src/SonsOfPHP/*/*/*/vendor/*',
        __DIR__ . '/src/SonsOfPHP/*/*/*/*/vendor/*',
    ])
    // This should be the same version that is found in composer.json file
    ->withPhpSets(php81: true)
    ->withRules([
        AddVoidReturnTypeWhereNoReturnRector::class,
    ]);
