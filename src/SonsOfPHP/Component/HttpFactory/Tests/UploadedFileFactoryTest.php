<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\HttpFactory\UploadedFileFactory;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\HttpFactory\UploadedFileFactory
 *
 * @internal
 */
final class UploadedFileFactoryTest extends TestCase
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
        $this->assertInstanceOf(UploadedFileFactoryInterface::class, new UploadedFileFactory());
    }

    /**
     * @covers ::createUploadedFile
     */
    public function testCreateUploadedFileWorksAsExpected(): void
    {
        $factory = new UploadedFileFactory();

        $this->assertInstanceOf(UploadedFileInterface::class, $factory->createUploadedFile($this->stream));
    }
}
