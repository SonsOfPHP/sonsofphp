<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\LiipImagine\Filesystem\Imagine\Cache\Resolver;

use Liip\ImagineBundle\Binary\BinaryInterface;
use Liip\ImagineBundle\Exception\Imagine\Cache\Resolver\NotResolvableException;
use Liip\ImagineBundle\Imagine\Cache\Resolver\ResolverInterface;
use SonsOfPHP\Contract\Filesystem\FilesystemInterface;

class SonsOfPHPFilesystemResolver implements ResolverInterface
{
    public function __construct(
        private readonly FilesystemInterface $filesystem,
        private string $webRoot,
        private string $cachePrefix = 'media/cache',
    ) {
        $this->webRoot = rtrim($webRoot, '/');
        $this->cachePrefix = ltrim(str_replace('//', '/', $cachePrefix), '/');
    }

    /**
     * Checks whether the given path is stored within this Resolver.
     *
     * @param string $path
     * @param string $filter
     *
     * @return bool
     */
    public function isStored($path, string $filter): bool
    {
        return $this->filesystem->exists($this->getFilePath($path, $filter));
    }

    /**
     * Resolves filtered path for rendering in the browser.
     *
     * @param string $path   The path where the original file is expected to be
     * @param string $filter The name of the imagine filter in effect
     *
     * @throws NotResolvableException
     *
     * @return string The absolute URL of the cached image
     */
    public function resolve($path, string $filter): string
    {
        return sprintf(
            '%s/%s',
            rtrim($this->webRoot, '/'),
            ltrim((string) $this->getFileUrl($path, $filter), '/'),
        );
    }

    /**
     * Stores the content of the given binary.
     *
     * @param BinaryInterface $binary The image binary to store
     * @param string          $path   The path where the original file is expected to be
     * @param string          $filter The name of the imagine filter in effect
     */
    public function store(BinaryInterface $binary, $path, string $filter): void
    {
        $this->filesystem->write(
            $this->getFilePath($path, $filter),
            $binary->getContent(),
            ['mimeType' => $binary->getMimeType()],
        );
    }

    /**
     * @param string[] $paths   The paths where the original files are expected to be
     * @param string[] $filters The imagine filters in effect
     */
    public function remove(array $paths, array $filters): void
    {
        if ($paths === [] && $filters === []) {
            return;
        }

        if ($paths === []) {
            foreach ($filters as $filter) {
                $filterCacheDir = $this->cachePrefix . '/' . $filter;
                $this->filesystem->delete($filterCacheDir);
            }

            return;
        }

        foreach ($paths as $path) {
            foreach ($filters as $filter) {
                if ($this->filesystem->exists($this->getFilePath($path, $filter))) {
                    $this->filesystem->delete($this->getFilePath($path, $filter));
                }
            }
        }
    }

    protected function getFilePath($path, string $filter): string
    {
        return $this->getFileUrl($path, $filter);
    }

    protected function getFileUrl($path, string $filter): string
    {
        // crude way of sanitizing URL scheme ("protocol") part
        $path = str_replace('://', '---', $path);

        return $this->cachePrefix . '/' . $filter . '/' . ltrim($path, '/');
    }
}
