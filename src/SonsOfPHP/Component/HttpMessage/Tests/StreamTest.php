<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpMessage\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use SonsOfPHP\Component\HttpMessage\Stream;

/**
 * @coversDefaultClass \SonsOfPHP\Component\HttpMessage\Stream
 *
 * @uses \SonsOfPHP\Component\HttpMessage\Stream
 */
final class StreamTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItImplementsCorrectInterface(): void
    {
        $stream = new Stream();
        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertInstanceOf(\Stringable::class, $stream);
    }

    /**
     * @dataProvider modeProvider
     *
     * @covers ::isReadable
     * @covers ::isWritable
     */
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

    /**
     * @covers ::__construct
     */
    public function testConstructWorksAsExpectedWhenNoStreamIsPassed(): void
    {
        $stream = new Stream();
        $this->assertTrue($stream->isSeekable());
        $this->assertTrue($stream->isReadable());
        $this->assertTrue($stream->isWritable());
    }

    /**
     * @covers ::__construct
     */
    public function testConstructWorksAsExpectedWhenStreamIsPassed(): void
    {
        $stream = new Stream(fopen('php://memory', 'w+'));
        $this->assertTrue($stream->isSeekable());
        $this->assertTrue($stream->isReadable());
        $this->assertTrue($stream->isWritable());
    }

    /**
     * @covers ::__construct
     */
    public function testConstructWorksAsExpectedWhenInvalidStreamIsPassed(): void
    {
        $this->expectException('InvalidArgumentException');
        $stream = new Stream(new \stdClass());
    }

    /**
     * @covers ::__toString
     */
    public function testStringableWorksAsExpected(): void
    {
        $stream = new Stream();
        $stream->write('just a test');
        $this->assertSame('', $stream->getContents());
        $this->assertSame('just a test', (string) $stream);
    }

    /**
     * @covers ::detach
     */
    public function testDetachWorksAsExpected(): void
    {
        $stream = new Stream();
        $resource = $stream->detach();
        $this->assertIsResource($resource);
        $this->assertNull($stream->detach());
    }

    /**
     * @covers ::getSize
     */
    public function testGetSizeWorksAsExpected(): void
    {
        $stream = new Stream();
        $this->assertSame(0, $stream->getSize());

        $stream->write('testing');
        $this->assertGreaterThan(0, $stream->getSize());

        $stream->close();
        $this->assertNull($stream->getSize());
    }

    /**
     * @covers ::__destruct
     * @covers ::close
     */
    public function testCloseWorksAsExpected(): void
    {
        $stream = new Stream();
        $this->assertTrue($stream->isReadable());
        $this->assertTrue($stream->isWritable());
        $stream->close();
        $this->assertFalse($stream->isReadable());
        $this->assertFalse($stream->isWritable());
    }

    /**
     * @covers ::tell
     */
    public function testTellWorksAsExpected(): void
    {
        $stream = new Stream();
        $this->assertSame(0, $stream->tell());

        $stream->write('just a test');
        $this->assertSame(11, $stream->tell());

        $stream->rewind();
        $this->assertSame(0, $stream->tell());
    }

    /**
     * @covers ::eof
     */
    public function testEofWorksAsExpected(): void
    {
        $stream = new Stream();
        $this->assertFalse($stream->eof());

        $contents = $stream->getContents();
        $this->assertTrue($stream->eof());
    }

    /**
     * @covers ::write
     */
    public function testWriteWorksAsExpected(): void
    {
        $stream = new Stream();
        $this->assertSame(11, $stream->write('just a test'), 'Assert that write() returns correct size');
    }

    /**
     * @covers ::write
     */
    public function testWriteWorksAsExpectedWhenStreamHasBeenClosed(): void
    {
        $stream = new Stream();
        $stream->close();
        $this->expectException('RuntimeException');
        $stream->write('test');
    }

    /**
     * @covers ::write
     */
    public function testWriteWorksAsExpectedWhenStreamHasBeenDetached(): void
    {
        $stream = new Stream();
        $stream->detach();
        $this->expectException('RuntimeException');
        $stream->write('test');
    }

    /**
     * @covers ::write
     */
    public function testWriteWorksAsExpectedWhenStreamIsNotWritable(): void
    {
        $stream = new Stream(fopen('php://memory', 'r'));
        $this->expectException('RuntimeException');
        $stream->write('test');
    }

    /**
     * @covers ::read
     */
    public function testReadWorksAsExpected(): void
    {
        $stream = new Stream();
        $this->assertSame('', $stream->read(1024));
    }

    /**
     * @covers ::read
     */
    public function testReadWorksAsExpectedWhenStreamHasBeenClosed(): void
    {
        $stream = new Stream();
        $stream->close();
        $this->expectException('RuntimeException');
        $stream->read(1024);
    }

    /**
     * @covers ::read
     */
    public function testReadWorksAsExpectedWhenStreamHasBeenDetached(): void
    {
        $stream = new Stream();
        $stream->detach();
        $this->expectException('RuntimeException');
        $stream->read(1024);
    }

    /**
     * @covers ::getContents
     */
    public function testGetContentsWorksAsExpected(): void
    {
        $stream = new Stream();
        $stream->write('just a test');
        $this->assertSame('', $stream->getContents());
        $stream->rewind();
        $this->assertSame('just a test', $stream->getContents());
    }

    /**
     * @covers ::getContents
     */
    public function testGetContentsWorksAsExpectedWhenStreamIsClosed(): void
    {
        $stream = new Stream();
        $stream->close();
        $this->expectException('RuntimeException');
        $stream->getContents();
    }

    /**
     * @covers ::getContents
     */
    public function testGetContentsWorksAsExpectedWhenStreamIsDetached(): void
    {
        $stream = new Stream();
        $stream->detach();
        $this->expectException('RuntimeException');
        $stream->getContents();
    }

    /**
     * @covers ::getMetadata
     */
    public function testGetMetadataWorksAsExpected(): void
    {
        $stream = new Stream();
        $this->assertIsArray($stream->getMetadata());
        $this->assertNull($stream->getMetadata('not_a_real_key'));
        $this->assertNotNull($stream->getMetadata('stream_type'));
        $this->assertNotNull($stream->getMetadata('wrapper_type'));
        $this->assertNotNull($stream->getMetadata('uri'));
    }

    /**
     * @covers ::getMetadata
     */
    public function testGetMetadataWorksAsExpectedWhenStreamHasBeenClosed(): void
    {
        $stream = new Stream();
        $stream->close();
        $this->assertNull($stream->getMetadata());
    }

    /**
     * @covers ::getMetadata
     */
    public function testGetMetadataWorksAsExpectedWhenStreamHasBeenDetached(): void
    {
        $stream = new Stream();
        $stream->detach();
        $this->assertNull($stream->getMetadata());
    }

    /**
     * @covers ::rewind
     */
    public function testRewindWorksAsExpected(): void
    {
        $stream = new Stream();
        $stream->write('test');
        $this->assertSame('', $stream->getContents());
        $stream->rewind();
        $this->assertSame('test', $stream->getContents());
    }

    /**
     * @covers ::seek
     */
    public function testSeekWorksAsExpected(): void
    {
        $stream = new Stream();
        $stream->write('test');
        $stream->seek(0);
        $this->assertSame('test', $stream->getContents());
    }

    /**
     * @covers ::seek
     */
    public function testSeekWorksAsExpectedWhenStreamHasBeenClosed(): void
    {
        $stream = new Stream();
        $stream->seek(0);
        $stream->close();

        $this->expectException('RuntimeException');
        $stream->seek(0);
    }

    /**
     * @covers ::seek
     */
    public function testSeekWorksAsExpectedWhenStreamHasBeenDetached(): void
    {
        $stream = new Stream();
        $stream->seek(0);
        $stream->detach();

        $this->expectException('RuntimeException');
        $stream->seek(0);
    }

    /**
     * @covers ::seek
     */
    public function testSeekWorksAsExpectedWhenOffsetIsNegative(): void
    {
        $stream = new Stream();
        $this->expectException('RuntimeException');
        $stream->seek(-1);
    }

    /**
     * @covers ::__toString
     */
    public function testToStringWorksAsExpectedWhenStreamHasBeenClosed(): void
    {
        $stream = new Stream();
        $stream->write('test');
        $this->assertSame('test', (string) $stream);

        $stream->close();
        $this->assertSame('', (string) $stream);
    }

    /**
     * @covers ::tell
     */
    public function testTellWorksAsExpectedWhenStreamHasBeenClosed(): void
    {
        $stream = new Stream();
        $this->assertSame(0, $stream->tell());
        $stream->close();

        $this->expectException('RuntimeException');
        $stream->tell();
    }

    /**
     * @covers ::tell
     */
    public function testTellWorksAsExpectedWhenStreamHasBeenDetached(): void
    {
        $stream = new Stream();
        $this->assertSame(0, $stream->tell());
        $stream->detach();

        $this->expectException('RuntimeException');
        $stream->tell();
    }

    /**
     * @covers ::eof
     */
    public function testEofWorksAsExpectedWhenStreamHasBeenClosed(): void
    {
        $stream = new Stream();
        $this->assertFalse($stream->eof());
        $stream->close();

        $this->expectException('RuntimeException');
        $stream->eof();
    }

    /**
     * @covers ::eof
     */
    public function testEofWorksAsExpectedWhenStreamHasBeenDetached(): void
    {
        $stream = new Stream();
        $this->assertFalse($stream->eof());
        $stream->detach();

        $this->expectException('RuntimeException');
        $stream->eof();
    }

    /**
     * @covers ::isSeekable
     */
    public function testIsSeekableWorksAsExpectedWhenStreamHasBeenClosed(): void
    {
        $stream = new Stream();
        $this->assertTrue($stream->isSeekable());
        $stream->close();
        $this->assertFalse($stream->isSeekable());
    }

    /**
     * @covers ::isSeekable
     */
    public function testIsSeekableWorksAsExpectedWhenStreamHasBeenDetached(): void
    {
        $stream = new Stream();
        $this->assertTrue($stream->isSeekable());
        $stream->detach();
        $this->assertFalse($stream->isSeekable());
    }
}
