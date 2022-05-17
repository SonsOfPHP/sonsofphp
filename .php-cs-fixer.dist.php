<?php

$finder = PhpCsFixer\Finder::create()
    ->in('packages/')
;

$config = new PhpCsFixer\Config();
return $config->setRules([
        // Rule sets
        '@PHP74Migration' => true,
        '@Symfony' => true,
    ])
    ->setFinder($finder)
;
