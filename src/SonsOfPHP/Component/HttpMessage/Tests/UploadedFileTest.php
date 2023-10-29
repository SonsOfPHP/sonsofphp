<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpMessage\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\HttpMessage\UploadedFile;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\HttpMessage\UploadedFile
 *
 * @internal
 */
final class UploadedFileTest extends TestCase
{
    private $stream;

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
}
