<?php

declare(strict_types=1);
use Rector\Config\RectorConfig;

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
    ->withPhpSets(
        php82: true,
    )
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
    )
    ->withImportNames(
        importShortClasses: false,
        removeUnusedImports: true,
    )
    ->withRules([
        // Generic
        //AddVoidReturnTypeWhereNoReturnRector::class,
        //ArrayKeyExistsTernaryThenValueToCoalescingRector::class,
        //ArrayMergeOfNonArraysToSimpleArrayRector::class,
        //BooleanNotIdenticalToNotIdenticalRector::class,
        //CleanupUnneededNullsafeOperatorRector::class,
        //CombineIfRector::class,
        //CombinedAssignRector::class,
        //ExplicitBoolCompareRector::class,
        //FlipTypeControlToUseExclusiveTypeRector::class,
        //ForRepeatedCountToOwnVariableRector::class,
        //Utf8DecodeEncodeToMbConvertEncodingRector::class,
        // PHPUnit Rules
        //AnnotationWithValueToAttributeRector::class,
        //AssertCompareToSpecificMethodRector::class,
        //AssertComparisonToSpecificMethodRector::class,
        //AssertEqualsToSameRector::class,
        //CoversAnnotationWithValueToAttributeRector::class,
        //DataProviderAnnotationToAttributeRector::class,
        //GetMockRector::class,
        //PublicDataProviderClassMethodRector::class,
        //StaticDataProviderClassMethodRector::class,
        //TicketAnnotationToAttributeRector::class,
        //YieldDataProviderRector::class,
    ]);
