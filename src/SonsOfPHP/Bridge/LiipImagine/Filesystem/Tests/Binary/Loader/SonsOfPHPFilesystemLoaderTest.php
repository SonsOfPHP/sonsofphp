<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\LiipImagine\Filesystem\Tests\Binary\Loader;

use Liip\ImagineBundle\Binary\BinaryInterface;
use Liip\ImagineBundle\Binary\Loader\LoaderInterface;
use Liip\ImagineBundle\Exception\Binary\Loader\NotLoadableException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\LiipImagine\Filesystem\Binary\Loader\SonsOfPHPFilesystemLoader;
use SonsOfPHP\Component\Filesystem\Exception\FileNotFoundException;
use SonsOfPHP\Contract\Filesystem\FilesystemInterface;
use Symfony\Component\Mime\MimeTypesInterface;

#[CoversClass(SonsOfPHPFilesystemLoader::class)]
final class SonsOfPHPFilesystemLoaderTest extends TestCase
{
    private FilesystemInterface $filesystem;
    private MimeTypesInterface $mimeTypes;
    private LoaderInterface $loader;

    protected function setUp(): void
    {
        $this->filesystem = $this->createMock(FilesystemInterface::class);

        $this->mimeTypes = $this->createMock(MimeTypesInterface::class);

        $this->loader = new SonsOfPHPFilesystemLoader($this->filesystem, $this->mimeTypes);
    }

    public function testItHasTheRightInterfaces(): void
    {
        $this->assertInstanceOf(LoaderInterface::class, $this->loader);
    }

    public function testItWillFindAndReturnABinaryObject(): void
    {
        $this->filesystem->method('mimeType')->willReturn('image/png');
        $this->filesystem->method('read')->willReturn('pretend this is binary data');

        $this->mimeTypes->method('getExtensions')->willReturn(['png']);

        $output = $this->loader->find('/path/to/file.png');
        $this->assertInstanceOf(BinaryInterface::class, $output);
    }

    public function testItWillThrowCorrectExceptionWhenFileNotFound(): void
    {
        $this->filesystem->method('read')->will($this->throwException(new FileNotFoundException()));
        ;

        $this->expectException(NotLoadableException::class);
        $this->loader->find('/path/to/file.png');
    }
}
