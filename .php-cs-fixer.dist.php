<?php

$finder = PhpCsFixer\Finder::create()
    ->in('packages/')
;

$config = new PhpCsFixer\Config();
return $config->setRules([
        // Rule sets
        '@PHP80Migration' => true,
        '@Symfony' => true,
        // Rules
        'phpdoc_order' => true,
        //'php_unit_test_class_requires_covers' => true, // @todo
    ])
    ->setFinder($finder)
;
