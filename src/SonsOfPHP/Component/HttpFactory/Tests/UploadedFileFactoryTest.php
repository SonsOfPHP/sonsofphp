<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UploadedFileInterface;
use SonsOfPHP\Component\HttpFactory\UploadedFileFactory;

/**
 * @internal
 * @coversNothing
 */
#[CoversClass(UploadedFileFactory::class)]
final class UploadedFileFactoryTest extends TestCase
{
    private MockObject $stream;

    protected function setUp(): void
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
     * @uses \SonsOfPHP\Component\HttpMessage\UploadedFile
     */
    public function testCreateUploadedFileWorksAsExpected(): void
    {
        $factory = new UploadedFileFactory();

        $this->assertInstanceOf(UploadedFileInterface::class, $factory->createUploadedFile($this->stream));
    }
}
