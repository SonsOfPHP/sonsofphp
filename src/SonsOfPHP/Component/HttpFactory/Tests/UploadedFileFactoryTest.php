<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UploadedFileInterface;
use SonsOfPHP\Component\HttpFactory\UploadedFileFactory;
use SonsOfPHP\Component\HttpMessage\UploadedFile;

#[CoversClass(UploadedFileFactory::class)]
#[UsesClass(UploadedFile::class)]
final class UploadedFileFactoryTest extends TestCase
{
    private MockObject $stream;

    protected function setUp(): void
    {
        $this->stream = $this->createMock(StreamInterface::class);
        $this->stream->method('isReadable')->willReturn(true);
    }

    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(UploadedFileFactoryInterface::class, new UploadedFileFactory());
    }

    public function testCreateUploadedFileWorksAsExpected(): void
    {
        $factory = new UploadedFileFactory();

        $this->assertInstanceOf(UploadedFileInterface::class, $factory->createUploadedFile($this->stream));
    }
}
