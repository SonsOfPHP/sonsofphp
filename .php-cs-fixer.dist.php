<?php

$finder = PhpCsFixer\Finder::create()
    ->in('packages/')
;

$config = new PhpCsFixer\Config();
return $config->setRules([
        // Rule sets
        '@PER-CS2.0' => true,
        '@PHP80Migration:risky' => true,
        '@PHP81Migration' => true,
        '@PHPUnit100Migration:risky' => true,
    ])
    ->setFinder($finder)
;
