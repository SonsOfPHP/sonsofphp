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
        // Rules
        'phpdoc_order' => true,
        //'php_unit_test_class_requires_covers' => true, // @todo
    ])
    ->setFinder($finder)
;
