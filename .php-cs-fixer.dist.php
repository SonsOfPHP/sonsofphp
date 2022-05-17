<?php

$finder = PhpCsFixer\Finder::create()
    ->in('packages/')
;

$config = new PhpCsFixer\Config();
return $config->setRules([
        '@PSR12' => true,
    ])
    ->setFinder($finder)
;
