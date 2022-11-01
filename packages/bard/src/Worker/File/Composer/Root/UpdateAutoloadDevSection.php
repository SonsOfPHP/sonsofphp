<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Worker\File\Composer\Root;

use SonsOfPHP\Bard\JsonFile;
use SonsOfPHP\Bard\Worker\WorkerInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class UpdateAutoloadDevSection implements WorkerInterface
{
    private JsonFile $pkgComposerJsonFile;

    public function __construct(JsonFile $pkgComposerJsonFile)
    {
        $this->pkgComposerJsonFile = $pkgComposerJsonFile;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(JsonFile $rootComposerJsonFile): JsonFile
    {
        $rootDir = pathinfo($rootComposerJsonFile->getFilename(), \PATHINFO_DIRNAME);
        $pkgDir = pathinfo($this->pkgComposerJsonFile->getFilename(), \PATHINFO_DIRNAME);
        $path = trim(str_replace($rootDir, '', $pkgDir), '/');

        $rootAutoloadSection = $rootComposerJsonFile->getSection('autoload-dev');
        $pkgAutoloadSection = $this->pkgComposerJsonFile->getSection('autoload-dev');

        if (null === $pkgAutoloadSection) {
            return $rootComposerJsonFile;
        }

        foreach ($pkgAutoloadSection as $section => $config) {
            if ('psr-4' === $section) {
                foreach ($config as $namespace => $pkgPath) {
                    $rootAutoloadSection['psr-4'][$namespace] = trim($path.'/'.trim($pkgPath, '/'), '/');
                }
            }

            if ('exclude-from-classmap' === $section) {
                foreach ($config as $pkgPath) {
                    $rootAutoloadSection['exclude-from-classmap'][] = trim($path.'/'.trim($pkgPath, '/'), '/');
                }
            }
        }

        if (isset($rootAutoloadSection['psr-4'])) {
            $rootAutoloadSection['psr-4'] = array_unique($rootAutoloadSection['psr-4']);
        }

        if (isset($rootAutoloadSection['exclude-from-classmap'])) {
            $rootAutoloadSection['exclude-from-classmap'] = array_unique($rootAutoloadSection['exclude-from-classmap']);
        }

        return $rootComposerJsonFile->setSection('autoload-dev', $rootAutoloadSection);
    }
}
