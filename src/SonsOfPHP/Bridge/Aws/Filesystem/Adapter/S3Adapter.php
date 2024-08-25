<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Aws\Filesystem\Adapter;

use Aws\S3\S3ClientInterface;
use SonsOfPHP\Component\Filesystem\Exception\FilesystemException;
use SonsOfPHP\Component\Filesystem\Exception\UnableToCopyFileException;
use SonsOfPHP\Component\Filesystem\Exception\UnableToDeleteDirectoryException;
use SonsOfPHP\Component\Filesystem\Exception\UnableToDeleteFileException;
use SonsOfPHP\Component\Filesystem\Exception\UnableToWriteFileException;
use SonsOfPHP\Contract\Filesystem\Adapter\AdapterInterface;
use SonsOfPHP\Contract\Filesystem\Adapter\CopyAwareInterface;
use SonsOfPHP\Contract\Filesystem\Adapter\DirectoryAwareInterface;
use SonsOfPHP\Contract\Filesystem\Adapter\MoveAwareInterface;
use SonsOfPHP\Contract\Filesystem\ContextInterface;
use Throwable;

/**
 * Usage:
 *   $adapter = new S3Adapter($s3Client, 'bucket-name');
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class S3Adapter implements AdapterInterface, CopyAwareInterface, DirectoryAwareInterface, MoveAwareInterface
{
    public function __construct(
        private S3ClientInterface $client,
        private string $bucket,
        private string $acl = 'private',
    ) {}

    public function add(string $path, mixed $contents, ?ContextInterface $context = null): void
    {
        $options = [
            'Bucket' => $this->bucket,
            'Prefix' => ltrim($path, '/'),
            'params' => [],
        ];
        if (isset($context['mimeType'])) {
            $options['params']['ContentType'] = $context['mimeType'];
        }
        try {
            $this->client->upload(
                $this->bucket,
                ltrim($path, '/'),
                $contents,
                $context['acl'] ?? $this->acl,
                $options,
            );
        } catch (Throwable $exception) {
            throw new UnableToWriteFileException($exception->getMessage());
        }
    }

    public function get(string $path, ?ContextInterface $context = null): mixed
    {
        $options = [
            'Bucket'    => $this->bucket,
            'Key'       => ltrim($path, '/'),
            'MaxKeys'   => 1,
            'Delimiter' => '/',
        ];
        $command = $this->client->getCommand('GetObject', $options);

        try {
            return (string) $this->client->execute($command)->get('Body')->getContents();
        } catch (Throwable) {
            throw new FilesystemException('Could not read file');
        }
    }

    public function remove(string $path, ?ContextInterface $context = null): void
    {
        $options = [
            'Bucket' => $this->bucket,
            'Key'    => ltrim($path, '/'),
        ];
        $command = $this->client->getCommand('DeleteObject', $options);

        try {
            $this->client->execute($command);
        } catch (Throwable $exception) {
            throw new UnableToDeleteFileException($exception->getMessage());
        }
    }

    public function has(string $path, ?ContextInterface $context = null): bool
    {
        if ($this->isFile($path, $context)) {
            return true;
        }
        return $this->isDirectory($path, $context);
    }

    public function isFile(string $path, ?ContextInterface $context = null): bool
    {
        try {
            return $this->client->doesObjectExistV2($this->bucket, ltrim($path, '/'), false);
        } catch (Throwable) {
            throw new FilesystemException('Unable to check if file exists');
        }
    }

    public function copy(string $source, string $destination, ?ContextInterface $context = null): void
    {
        try {
            $this->client->copy(
                $this->bucket,
                ltrim($source, '/'),
                $this->bucket,
                ltrim($destination, '/'),
                $context['acl'] ?? $this->acl,
            );
        } catch (Throwable) {
            throw new UnableToCopyFileException();
        }
    }

    public function isDirectory(string $path, ?ContextInterface $context = null): bool
    {
        $options = [
            'Bucket'    => $this->bucket,
            'Prefix'    => ltrim($path, '/'),
            'MaxKeys'   => 1,
            'Delimiter' => '/',
        ];
        $command = $this->client->getCommand('ListObjectsV2', $options);

        try {
            $result = $this->client->execute($command);
            if ($result->hasKey('Contents')) {
                return true;
            }
            return $result->hasKey('CommonPrefixes');
        } catch (Throwable) {
            throw new FilesystemException('Could not figure out if directory exists');
        }
    }

    public function makeDirectory(string $path, ?ContextInterface $context = null): void
    {
        $this->write($path, '', $context);
    }

    public function removeDirectory(string $path, ?ContextInterface $context = null): void
    {
        try {
            $this->client->deleteMatchingObjects($this->bucket, $path);
        } catch (Throwable) {
            throw new UnableToDeleteDirectoryException();
        }
    }

    public function move(string $source, string $destination, ?ContextInterface $context = null): void
    {
        try {
            $this->copy($source, $destination, $context);
            $this->delete($source);
        } catch (Throwable) {
            throw new UnableToMoveFile();
        }
    }

    public function mimeType(string $path, ?ContextInterface $context = null): string
    {
        $options = [
            'Bucket' => $this->bucket,
            'Key'    => ltrim($path, '/'),
        ];
        $command = $this->client->getCommand('HeadObject', $options);

        try {
            $result = $this->client->execute($command);
        } catch (Throwable) {
            throw new FilesystemException();
        }

        return $result['ContentType'];
    }
}
