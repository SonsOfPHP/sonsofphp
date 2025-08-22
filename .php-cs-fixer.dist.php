<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

$finder = Finder::create()
    ->in(__DIR__)
    ->in(__DIR__ . '/tools/chorale/src/')
    ->exclude('build')
    ->exclude('docs')
    ->exclude('tools')
    ->exclude('vendor')
;

return (new Config())
    ->setUsingCache(true)
    ->setCacheFile(__DIR__ . '/build/cache/php-cs-fixer/php-cs-fixer.cache')
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setRules([
        // Rule sets
        '@PER-CS' => true,
        '@PHP82Migration:risky' => true,
        '@PHP83Migration' => true,
        '@PHPUnit100Migration:risky' => true,

        // Rules
        'no_unused_imports' => true,
        'ordered_imports' => true,
        'heredoc_indentation' => false,
        //'php_unit_test_class_requires_covers' => true,
    ])
    ->setFinder($finder)
;
