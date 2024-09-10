<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\LiipImagine\Filesystem\Tests\Imagine\Cache\Resolver;

use Liip\ImagineBundle\Imagine\Cache\Resolver\ResolverInterface;
use Liip\ImagineBundle\Model\Binary;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\LiipImagine\Filesystem\Imagine\Cache\Resolver\SonsOfPHPFilesystemResolver;
use SonsOfPHP\Component\Filesystem\Adapter\InMemoryAdapter;
use SonsOfPHP\Component\Filesystem\Context;
use SonsOfPHP\Component\Filesystem\Filesystem;
use SonsOfPHP\Contract\Filesystem\FilesystemInterface;

#[CoversClass(SonsOfPHPFilesystemResolver::class)]
#[UsesClass(InMemoryAdapter::class)]
#[UsesClass(Context::class)]
#[UsesClass(Filesystem::class)]
final class SonsOfPHPFilesystemResolverTest extends TestCase
{
    private ResolverInterface $resolver;

    private FilesystemInterface $filesystem;

    private string $webRoot = 'https://images.internal';

    protected function setUp(): void
    {
        $this->filesystem = new Filesystem(new InMemoryAdapter());

        $this->resolver = new SonsOfPHPFilesystemResolver($this->filesystem, $this->webRoot);
    }

    public function testItHasTheRightInterface(): void
    {
        $this->assertInstanceOf(ResolverInterface::class, $this->resolver);
    }

    public function testItWillReturnTrueWhenFileIsStored(): void
    {
        $this->filesystem->write('media/cache/cache/path/to/file.ext', 'contents');
        $this->assertTrue($this->resolver->isStored('/path/to/file.ext', 'cache'));
    }

    public function testItWillReturnFlaseWhenFileIsNotStored(): void
    {
        $this->assertFalse($this->resolver->isStored('/path/to/file.ext', 'cache'));
    }

    public function testItCanResolve(): void
    {
        $this->assertSame('https://images.internal/media/cache/cache/path/to/file.ext', $this->resolver->resolve('/path/to/file.ext', 'cache'));
    }

    public function testItCanStore(): void
    {
        $binary = new Binary('contents', 'image/png', 'png');
        $this->resolver->store($binary, '/path/to/file.ext', 'cache');
        $this->assertTrue($this->filesystem->exists('/media/cache/cache/path/to/file.ext'));
    }

    #[DoesNotPerformAssertions]
    public function testItCanRemoveWhenPathsAndFiltersAreEmtpty(): void
    {
        $this->resolver->remove([], []);
    }

    public function testItCanRemoveByFilters(): void
    {
        $this->filesystem->write('media/cache/cache/path/to/file.ext', 'contents');
        $this->resolver->remove([], ['cache']);
        $this->assertFalse($this->filesystem->exists('/media/cache/cache/path/to/file.ext'));
    }

    public function testItCanRemoveByPathsAndFilters(): void
    {
        $this->filesystem->write('/media/cache/cache/path/to/file.ext', 'contents');
        $this->filesystem->write('/media/cache/cache/path/to/another.ext', 'contents');

        $this->resolver->remove(['/path/to/file.ext'], ['cache']);
        $this->assertFalse($this->filesystem->exists('/media/cache/cache/path/to/file.ext'));
        $this->assertTrue($this->filesystem->exists('/media/cache/cache/path/to/another.ext'));
    }
}
