<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpMessage\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use SonsOfPHP\Component\HttpMessage\UploadedFile;
use SonsOfPHP\Component\HttpMessage\UploadedFileError;

/**
 * @uses \SonsOfPHP\Component\HttpMessage\UploadedFile
 * @coversNothing
 */
#[CoversClass(UploadedFile::class)]
final class UploadedFileTest extends TestCase
{
    private MockObject $stream;

    public function setUp(): void
    {
        $this->stream = $this->createMock(StreamInterface::class);
        $this->stream->method('isReadable')->willReturn(true);
    }

    /**
     * @coversNothing
     */
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(UploadedFileInterface::class, new UploadedFile($this->stream));
    }

    public function testConstructWillThrowExceptionWhenStreamIsNull(): void
    {
        $this->expectException('InvalidArgumentException');
        new UploadedFile(null);
    }

    public function testConstructWillThrowExceptionWhenErrorIsInvalid(): void
    {
        $this->expectException('InvalidArgumentException');
        new UploadedFile(stream: $this->stream, error: 99999);
    }

    public function testConstructWillThrowExceptionWhenStreamIsUnreadable(): void
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('isReadable')->willReturn(false);

        $this->expectException('InvalidArgumentException');
        new UploadedFile($stream);
    }

    public function testGetStreamWorksAsExpected(): void
    {
        $file = new UploadedFile($this->stream);
        $this->assertSame($this->stream, $file->getStream());
    }

    public function testGetSizeWorksAsExpeected(): void
    {
        $file = new UploadedFile(
            stream: $this->stream,
            size: 2131,
        );

        $this->assertSame(2131, $file->getSize());
    }

    public function testGetSizeWorksWhenSizeIsNotManuallySet(): void
    {
        $this->stream->method('getSize')->willReturn(2131);
        $file = new UploadedFile($this->stream);
        $this->assertSame(2131, $file->getSize());
    }

    public function testGetErrorWorksAsExpected(): void
    {
        $file = new UploadedFile($this->stream, error: UploadedFileError::CANT_WRITE->value);
        $this->assertSame(UploadedFileError::CANT_WRITE->value, $file->getError());
    }

    public function testGetClientFilenameWorksAsExpected(): void
    {
        $file = new UploadedFile($this->stream, clientFilename: 'testing.txt');
        $this->assertSame('testing.txt', $file->getClientFilename());
    }

    public function testGetClientMediaTypeWorksAsExpected(): void
    {
        $file = new UploadedFile($this->stream, clientMediaType: 'media-type');
        $this->assertSame('media-type', $file->getClientMediaType());
    }
}
