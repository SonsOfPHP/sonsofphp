<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\LiipImagine\Filesystem\Binary\Loader;

use Liip\ImagineBundle\Binary\Loader\LoaderInterface;
use Liip\ImagineBundle\Exception\Binary\Loader\NotLoadableException;
use Liip\ImagineBundle\Model\Binary;
use SonsOfPHP\Contract\Filesystem\Exception\FilesystemExceptionInterface;
use SonsOfPHP\Contract\Filesystem\FilesystemInterface;
use Symfony\Component\Mime\MimeTypesInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class SonsOfPHPFilesystemLoader implements LoaderInterface
{
    public function __construct(
        private readonly FilesystemInterface $filesystem,
        private readonly MimeTypesInterface $extensionGuesser,
    ) {}

    public function find($path)
    {
        try {
            $mimeType = $this->filesystem->mimeType($path);
            $extension = $this->extensionGuesser->getExtensions($mimeType)[0] ?? null;

            return new Binary(
                $this->filesystem->read($path),
                $mimeType,
                $extension,
            );
        } catch (FilesystemExceptionInterface $exception) {
            throw new NotLoadableException(sprintf('Source image "%s" not found.', $path), 0, $exception);
        }
    }
}
