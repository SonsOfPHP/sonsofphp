<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('docs')
    ->exclude('tools')
    ->exclude('vendor')
;

$config = new PhpCsFixer\Config();
return $config->setRules([
    // Rule sets
    '@PER-CS' => true,
    '@PHP80Migration:risky' => true,
    '@PHP81Migration' => true,
    '@PHPUnit100Migration:risky' => true,
])->setFinder($finder);
