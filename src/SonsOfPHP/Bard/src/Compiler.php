<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard;

use Symfony\Component\Finder\Finder;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Compiler
{
    public function compile(string $pharFile = 'bard.phar'): void
    {
        if (file_exists($pharFile)) {
            unlink($pharFile);
        }

        $phar = new \Phar($pharFile, 0, 'bard.phar');
        $phar->setSignatureAlgorithm(\Phar::SHA512);
        $phar->startBuffering();

        // >>>
        // Add source files
        $finder = new Finder();
        $finder->files()
            ->ignoreVCS(true)
            ->name('*.php')
            ->notPath('Tests')
            ->notName('Compiler.php')
            ->in(__DIR__)
        ;

        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            $this->addFile($phar, $file);
        }

        // <<<

        // >>>
        // Add vendor files
        $finder = new Finder();
        $finder->files()
            ->ignoreVCS(true)
            ->notPath('docs')
            ->notPath('Tests')
            ->notPath('tests')
            ->in(__DIR__ . '/../vendor')
        ;
        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            $this->addFile($phar, $file);
        }

        // <<<

        $this->addBin($phar);
        $this->setStub($phar);
        $phar->stopBuffering();
    }

    private function addFile(\Phar $phar, \SplFileInfo $file): void
    {
        $contents = file_get_contents((string) $file);

        $phar->addFromString($this->getLocalName($file), $contents);
    }

    private function getLocalName(\SplFileInfo $file): string
    {
        $realPath   = $file->getRealPath();
        $pathPrefix = dirname(__DIR__, 1) . DIRECTORY_SEPARATOR;
        $pos        = strpos($realPath, $pathPrefix);

        $relativePath = ($pos !== false) ? substr_replace($realPath, '', $pos, strlen($pathPrefix)) : $realPath;

        return strtr($relativePath, '\\', '/');
    }

    private function addBin(\Phar $phar): void
    {
        $contents = file_get_contents(__DIR__ . '/../bin/bard');
        $contents = preg_replace('{^#!/usr/bin/env php\s*}', '', $contents);

        $phar->addFromString('bin/bard', $contents);
    }

    private function setStub(\Phar $phar): void
    {
        $phar->setStub($this->getStub());
    }

    private function getStub(): string
    {
        return <<<'STUB'
            #!/usr/bin/env php
            <?php
            Phar::mapPhar('bard.phar');
            require 'phar://bard.phar/bin/bard';
            __HALT_COMPILER();
            STUB;
    }
}
