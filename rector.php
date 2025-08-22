<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    //->withParallel()
    ->withCache(__DIR__ . '/build/cache/rector')
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tools/chorale',
    ])
    ->withSkip([
        __DIR__ . '/src/SonsOfPHP/*/vendor/*',
        __DIR__ . '/src/SonsOfPHP/*/*/vendor/*',
        __DIR__ . '/src/SonsOfPHP/*/*/*/vendor/*',
        __DIR__ . '/src/SonsOfPHP/*/*/*/*/vendor/*',
        __DIR__ . '*/vendor/*',
    ])
    // This should be the same version that is found in composer.json file
    ->withPhpSets(
        php83: true,
    )
    //->withAttributesSets(
    //    phpunit: true,
    //)
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        //privatization: true,
        //naming: true,
        instanceOf: true,
        earlyReturn: true,
        strictBooleans: true,
        phpunitCodeQuality: true,
        //phpunit: true,
    )
    ->withImportNames(
        importShortClasses: false,
        removeUnusedImports: true,
    )
;
