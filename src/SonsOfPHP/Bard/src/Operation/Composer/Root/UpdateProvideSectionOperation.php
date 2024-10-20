<?php

declare(strict_types=1);

namespace SonsOfPHP\Bard\Operation\Composer\Root;

use SonsOfPHP\Bard\JsonFileInterface;
use SonsOfPHP\Bard\Operation\OperationInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class UpdateProvideSectionOperation implements OperationInterface
{
    public function __construct(private JsonFileInterface $pkgComposerJsonFile) {}

    public function apply(JsonFileInterface $rootComposerJsonFile): JsonFileInterface
    {
        $pkgProvideSection = $this->pkgComposerJsonFile->getSection('provide');
        if (null === $pkgProvideSection) {
            return $rootComposerJsonFile;
        }

        /** @var array<string, string> $rootProvideSection */
        $rootProvideSection = $rootComposerJsonFile->getSection('provide');

        foreach ($pkgProvideSection as $pkg => $version) {
            $rootProvideSection[$pkg] = $version;
        }

        ksort($rootProvideSection);

        return $rootComposerJsonFile->setSection('provide', $rootProvideSection);
    }
}
