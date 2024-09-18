<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Adapter;

use Psr\Cache\CacheItemInterface;
use SonsOfPHP\Component\Cache\CacheItem;
use SonsOfPHP\Component\Cache\Exception\CacheException;
use SonsOfPHP\Component\Cache\Marshaller\MarshallerInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class FilesystemAdapter extends AbstractAdapter
{
    public function __construct(
        private ?string $directory = null,
        private int $defaultPermission = 0777,
        int $defaultTTL = 0,
        ?MarshallerInterface $marshaller = null,
    )
    {
        parent::__construct(
            defaultTTL: $defaultTTL,
            marshaller: $marshaller,
        );

        if (null !== $this->directory) {
            $this->directory = realpath($this->directory) ?: $this->directory;
        }

        if (null === $this->directory) {
            $this->directory = sys_get_temp_dir().\DIRECTORY_SEPARATOR.'sonsofphp-cache';
        }

        if (!is_dir($this->directory)) {
            @mkdir($this->directory, $this->defaultPermission, true);
        }

        $this->directory .= \DIRECTORY_SEPARATOR;
    }

    /**
     * {@inheritdoc}
     */
    public function getItem(string $key): CacheItemInterface
    {
        if ($this->hasItem($key)) {
            return (new CacheItem($key, true))
                ->set($this->marshaller->unmarshall(file_get_contents($this->getFile($key))))
            ;
        }

        return new CacheItem($key, false);
    }

    /**
     * {@inheritdoc}
     */
    public function hasItem(string $key): bool
    {
        CacheItem::validateKey($key);

        $filename = $this->getFile($key);

        return file_exists($filename) && filemtime($filename) > time();
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): bool
    {
        if (is_dir($this->directory)) {
            // Prune all files and directories
            $it = new \RecursiveDirectoryIterator($this->directory, \RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
            foreach ($files as $file) {
                if ($file->isDir()) {
                    rmdir($file->getPathname());
                } else {
                    unlink($file->getPathname());
                }
            }
            return rmdir($this->directory);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItem(string $key): bool
    {
        CacheItem::validateKey($key);

        if (file_exists($this->getFile($key))) {
            return unlink($this->getFile($key));
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function save(CacheItemInterface $item): bool
    {
        if (!is_dir($this->directory)) {
            @mkdir($this->directory, $this->defaultPermission, true);
        }

        if (false === file_put_contents($this->getFile($item->getKey()), $this->marshaller->marshall($item->get()))) {
            return false;
        }

        if (false === chmod($this->getFile($item->getKey()), $this->defaultPermission)) {
            return false;
        }

        if (null === $item->expiry()) {
            $item->expiresAfter($this->defaultTTL);
        }
        $mtime = (int) round($item->expiry());
        return touch($this->getFile($item->getKey()), $mtime);
    }

    /**
     * Returns the full path and file name
     */
    private function getFile(string $key): string
    {
        $hash = str_replace('/', '-', base64_encode(hash('xxh128', static::class.$key, true)));
        $dir = $this->directory.strtoupper($hash[0].\DIRECTORY_SEPARATOR.$hash[1].\DIRECTORY_SEPARATOR);
        @mkdir($dir, $this->defaultPermission, true);

        return $dir.substr($hash, 2, 20);
    }
}
