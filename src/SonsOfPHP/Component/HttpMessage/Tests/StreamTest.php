<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpMessage\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use SonsOfPHP\Component\HttpMessage\Stream;
use stdClass;
use Stringable;

#[CoversClass(Stream::class)]
#[UsesClass(Stream::class)]
final class StreamTest extends TestCase
{
    public function testItImplementsCorrectInterface(): void
    {
        $stream = new Stream();
        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertInstanceOf(Stringable::class, $stream);
    }


    #[DataProvider('modeProvider')]
    public function testStreamModes(string $mode, bool $isReadable, bool $isWritable): void
    {
        $stream = new Stream(fopen('php://memory', $mode));
        $this->assertSame($isReadable, $stream->isReadable(), sprintf('Assert Stream is Readable with mode "%s"', $stream->getMetadata('mode')));
        $this->assertSame($isWritable, $stream->isWritable(), sprintf('Assert Stream is Writable with mode "%s"', $stream->getMetadata('mode')));
    }

    public static function modeProvider(): iterable
    {
        yield 'r'  => ['r', true, false];
        yield 'r+' => ['r+', true, true];
        //yield 'w'  => ['w', false, true];
        yield 'w+' => ['w+', true, true];
        //yield 'a'  => ['a', false, true];
        yield 'a+' => ['a+', true, true];
        //yield 'x'  => ['x', false, true];
        yield 'x+' => ['x+', true, true];
        //yield 'c'  => ['c', false, true];
        yield 'c+' => ['c+', true, true];
    }

    public function testConstructWorksAsExpectedWhenNoStreamIsPassed(): void
    {
        $stream = new Stream();
        $this->assertTrue($stream->isSeekable());
        $this->assertTrue($stream->isReadable());
        $this->assertTrue($stream->isWritable());
    }

    public function testConstructWorksAsExpectedWhenStreamIsPassed(): void
    {
        $stream = new Stream(fopen('php://memory', 'w+'));
        $this->assertTrue($stream->isSeekable());
        $this->assertTrue($stream->isReadable());
        $this->assertTrue($stream->isWritable());
    }

    public function testConstructWorksAsExpectedWhenInvalidStreamIsPassed(): void
    {
        $this->expectException('InvalidArgumentException');
        new Stream(new stdClass());
    }

    public function testStringableWorksAsExpected(): void
    {
        $stream = new Stream();
        $stream->write('just a test');
        $this->assertSame('', $stream->getContents());
        $this->assertSame('just a test', (string) $stream);
    }

    public function testDetachWorksAsExpected(): void
    {
        $stream = new Stream();
        $resource = $stream->detach();
        $this->assertIsResource($resource);
        $this->assertNull($stream->detach());
    }

    public function testGetSizeWorksAsExpected(): void
    {
        $stream = new Stream();
        $this->assertSame(0, $stream->getSize());

        $stream->write('testing');
        $this->assertGreaterThan(0, $stream->getSize());

        $stream->close();
        $this->assertNull($stream->getSize());
    }

    public function testCloseWorksAsExpected(): void
    {
        $stream = new Stream();
        $this->assertTrue($stream->isReadable());
        $this->assertTrue($stream->isWritable());
        $stream->close();
        $this->assertFalse($stream->isReadable());
        $this->assertFalse($stream->isWritable());
    }

    public function testTellWorksAsExpected(): void
    {
        $stream = new Stream();
        $this->assertSame(0, $stream->tell());

        $stream->write('just a test');
        $this->assertSame(11, $stream->tell());

        $stream->rewind();
        $this->assertSame(0, $stream->tell());
    }

    public function testEofWorksAsExpected(): void
    {
        $stream = new Stream();
        $this->assertFalse($stream->eof());

        $stream->getContents();
        $this->assertTrue($stream->eof());
    }

    public function testWriteWorksAsExpected(): void
    {
        $stream = new Stream();
        $this->assertSame(11, $stream->write('just a test'), 'Assert that write() returns correct size');
    }

    public function testWriteWorksAsExpectedWhenStreamHasBeenClosed(): void
    {
        $stream = new Stream();
        $stream->close();
        $this->expectException('RuntimeException');
        $stream->write('test');
    }

    public function testWriteWorksAsExpectedWhenStreamHasBeenDetached(): void
    {
        $stream = new Stream();
        $stream->detach();
        $this->expectException('RuntimeException');
        $stream->write('test');
    }

    public function testWriteWorksAsExpectedWhenStreamIsNotWritable(): void
    {
        $stream = new Stream(fopen('php://memory', 'r'));
        $this->expectException('RuntimeException');
        $stream->write('test');
    }

    public function testReadWorksAsExpected(): void
    {
        $stream = new Stream();
        $this->assertSame('', $stream->read(1024));
    }

    public function testReadWorksAsExpectedWhenStreamHasBeenClosed(): void
    {
        $stream = new Stream();
        $stream->close();
        $this->expectException('RuntimeException');
        $stream->read(1024);
    }

    public function testReadWorksAsExpectedWhenStreamHasBeenDetached(): void
    {
        $stream = new Stream();
        $stream->detach();
        $this->expectException('RuntimeException');
        $stream->read(1024);
    }

    public function testGetContentsWorksAsExpected(): void
    {
        $stream = new Stream();
        $stream->write('just a test');
        $this->assertSame('', $stream->getContents());
        $stream->rewind();
        $this->assertSame('just a test', $stream->getContents());
    }

    public function testGetContentsWorksAsExpectedWhenStreamIsClosed(): void
    {
        $stream = new Stream();
        $stream->close();
        $this->expectException('RuntimeException');
        $stream->getContents();
    }

    public function testGetContentsWorksAsExpectedWhenStreamIsDetached(): void
    {
        $stream = new Stream();
        $stream->detach();
        $this->expectException('RuntimeException');
        $stream->getContents();
    }

    public function testGetMetadataWorksAsExpected(): void
    {
        $stream = new Stream();
        $this->assertIsArray($stream->getMetadata());
        $this->assertNull($stream->getMetadata('not_a_real_key'));
        $this->assertNotNull($stream->getMetadata('stream_type'));
        $this->assertNotNull($stream->getMetadata('wrapper_type'));
        $this->assertNotNull($stream->getMetadata('uri'));
    }

    public function testGetMetadataWorksAsExpectedWhenStreamHasBeenClosed(): void
    {
        $stream = new Stream();
        $stream->close();
        $this->assertNull($stream->getMetadata());
    }

    public function testGetMetadataWorksAsExpectedWhenStreamHasBeenDetached(): void
    {
        $stream = new Stream();
        $stream->detach();
        $this->assertNull($stream->getMetadata());
    }

    public function testRewindWorksAsExpected(): void
    {
        $stream = new Stream();
        $stream->write('test');
        $this->assertSame('', $stream->getContents());
        $stream->rewind();
        $this->assertSame('test', $stream->getContents());
    }

    public function testSeekWorksAsExpected(): void
    {
        $stream = new Stream();
        $stream->write('test');
        $stream->seek(0);
        $this->assertSame('test', $stream->getContents());
    }

    public function testSeekWorksAsExpectedWhenStreamHasBeenClosed(): void
    {
        $stream = new Stream();
        $stream->seek(0);
        $stream->close();

        $this->expectException('RuntimeException');
        $stream->seek(0);
    }

    public function testSeekWorksAsExpectedWhenStreamHasBeenDetached(): void
    {
        $stream = new Stream();
        $stream->seek(0);
        $stream->detach();

        $this->expectException('RuntimeException');
        $stream->seek(0);
    }

    public function testSeekWorksAsExpectedWhenOffsetIsNegative(): void
    {
        $stream = new Stream();
        $this->expectException('RuntimeException');
        $stream->seek(-1);
    }

    public function testToStringWorksAsExpectedWhenStreamHasBeenClosed(): void
    {
        $stream = new Stream();
        $stream->write('test');
        $this->assertSame('test', (string) $stream);

        $stream->close();
        $this->assertSame('', (string) $stream);
    }

    public function testTellWorksAsExpectedWhenStreamHasBeenClosed(): void
    {
        $stream = new Stream();
        $this->assertSame(0, $stream->tell());
        $stream->close();

        $this->expectException('RuntimeException');
        $stream->tell();
    }

    public function testTellWorksAsExpectedWhenStreamHasBeenDetached(): void
    {
        $stream = new Stream();
        $this->assertSame(0, $stream->tell());
        $stream->detach();

        $this->expectException('RuntimeException');
        $stream->tell();
    }

    public function testEofWorksAsExpectedWhenStreamHasBeenClosed(): void
    {
        $stream = new Stream();
        $this->assertFalse($stream->eof());
        $stream->close();

        $this->expectException('RuntimeException');
        $stream->eof();
    }

    public function testEofWorksAsExpectedWhenStreamHasBeenDetached(): void
    {
        $stream = new Stream();
        $this->assertFalse($stream->eof());
        $stream->detach();

        $this->expectException('RuntimeException');
        $stream->eof();
    }

    public function testIsSeekableWorksAsExpectedWhenStreamHasBeenClosed(): void
    {
        $stream = new Stream();
        $this->assertTrue($stream->isSeekable());
        $stream->close();
        $this->assertFalse($stream->isSeekable());
    }

    public function testIsSeekableWorksAsExpectedWhenStreamHasBeenDetached(): void
    {
        $stream = new Stream();
        $this->assertTrue($stream->isSeekable());
        $stream->detach();
        $this->assertFalse($stream->isSeekable());
    }
}
