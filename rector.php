<?php

declare(strict_types=1);

use Rector\Php82\Rector\FuncCall\Utf8DecodeEncodeToMbConvertEncodingRector;
use Rector\CodeQuality\Rector\Assign\CombinedAssignRector;
use Rector\CodeQuality\Rector\For_\ForRepeatedCountToOwnVariableRector;
use Rector\CodeQuality\Rector\FuncCall\ArrayMergeOfNonArraysToSimpleArrayRector;
use Rector\CodeQuality\Rector\Identical\BooleanNotIdenticalToNotIdenticalRector;
use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\CodeQuality\Rector\If_\CombineIfRector;
use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\CodeQuality\Rector\NullsafeMethodCall\CleanupUnneededNullsafeOperatorRector;
use Rector\CodeQuality\Rector\Ternary\ArrayKeyExistsTernaryThenValueToCoalescingRector;
use Rector\Config\RectorConfig;
use Rector\PHPUnit\AnnotationsToAttributes\Rector\Class_\AnnotationWithValueToAttributeRector;
use Rector\PHPUnit\AnnotationsToAttributes\Rector\Class_\CoversAnnotationWithValueToAttributeRector;
use Rector\PHPUnit\AnnotationsToAttributes\Rector\Class_\TicketAnnotationToAttributeRector;
use Rector\PHPUnit\AnnotationsToAttributes\Rector\ClassMethod\DataProviderAnnotationToAttributeRector;
use Rector\PHPUnit\CodeQuality\Rector\Class_\YieldDataProviderRector;
use Rector\PHPUnit\CodeQuality\Rector\MethodCall\AssertCompareToSpecificMethodRector;
use Rector\PHPUnit\CodeQuality\Rector\MethodCall\AssertComparisonToSpecificMethodRector;
use Rector\PHPUnit\CodeQuality\Rector\MethodCall\AssertEqualsToSameRector;
use Rector\PHPUnit\PHPUnit100\Rector\Class_\PublicDataProviderClassMethodRector;
use Rector\PHPUnit\PHPUnit100\Rector\Class_\StaticDataProviderClassMethodRector;
use Rector\PHPUnit\PHPUnit50\Rector\StaticCall\GetMockRector;
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
        //phpunit: true,
        phpunitCodeQuality: true,
    )
    ->withImportNames(
        importShortClasses: false,
        removeUnusedImports: true,
    )
    ->withRules([
        // Generic
        AddVoidReturnTypeWhereNoReturnRector::class,
        ArrayKeyExistsTernaryThenValueToCoalescingRector::class,
        ArrayMergeOfNonArraysToSimpleArrayRector::class,
        BooleanNotIdenticalToNotIdenticalRector::class,
        CleanupUnneededNullsafeOperatorRector::class,
        CombineIfRector::class,
        CombinedAssignRector::class,
        ExplicitBoolCompareRector::class,
        FlipTypeControlToUseExclusiveTypeRector::class,
        ForRepeatedCountToOwnVariableRector::class,
        Utf8DecodeEncodeToMbConvertEncodingRector::class,
        // PHPUnit Rules
        AnnotationWithValueToAttributeRector::class,
        AssertCompareToSpecificMethodRector::class,
        AssertComparisonToSpecificMethodRector::class,
        AssertEqualsToSameRector::class,
        CoversAnnotationWithValueToAttributeRector::class,
        DataProviderAnnotationToAttributeRector::class,
        GetMockRector::class,
        PublicDataProviderClassMethodRector::class,
        StaticDataProviderClassMethodRector::class,
        TicketAnnotationToAttributeRector::class,
        YieldDataProviderRector::class,
    ]);
