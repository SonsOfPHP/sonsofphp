<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('docs')
    ->exclude('tools')
    ->exclude('vendor')
;

return (new PhpCsFixer\Config())->setRules([
    // Rule sets
    '@PER-CS' => true,
    '@PHP80Migration:risky' => true,
    '@PHP81Migration' => true,
    '@PHPUnit100Migration:risky' => true,

    // Rules
    'no_unused_imports' => true,
    'php_unit_test_class_requires_covers' => true,
])->setFinder($finder);
