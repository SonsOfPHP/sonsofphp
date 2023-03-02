<?php

$finder = PhpCsFixer\Finder::create()
    ->in('packages/')
;

$config = new PhpCsFixer\Config();
return $config->setRules([
        // Rule sets
        '@PHP80Migration:risky' => true,
        '@PHP81Migration' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PHPUnit84Migration:risky' => true,

        // PHPUnit Rules
        'php_unit_internal_class' => ['types' => ['normal', 'final']],
        'php_unit_test_case_static_method_calls' => ['call_type' => 'this'],
        'php_unit_test_class_requires_covers' => true,

        // Rules
        'align_multiline_comment' => true,
        'phpdoc_line_span' => true,
        'phpdoc_order_by_value' => ['annotations' => ['covers', 'throws']],
        'phpdoc_order' => true,
        'phpdoc_tag_casing' => true,
        'array_indentation' => true,
        'explicit_indirect_variable' => true,
        'binary_operator_spaces' => ['default' => 'align_single_space'],
        'phpdoc_to_comment' => false,
    ])
    ->setFinder($finder)
;
