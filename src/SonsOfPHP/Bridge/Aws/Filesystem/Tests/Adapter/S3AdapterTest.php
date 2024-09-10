<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Aws\Filesystem\Tests\Adapter;

use Aws\CommandInterface;
use Aws\ResultInterface;
use Aws\S3\S3ClientInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use SonsOfPHP\Bridge\Aws\Filesystem\Adapter\S3Adapter;
use SonsOfPHP\Contract\Filesystem\Adapter\AdapterInterface;
use SonsOfPHP\Contract\Filesystem\Adapter\CopyAwareInterface;
use SonsOfPHP\Contract\Filesystem\Adapter\DirectoryAwareInterface;
use SonsOfPHP\Contract\Filesystem\Adapter\MoveAwareInterface;

#[CoversClass(S3Adapter::class)]
final class S3AdapterTest extends TestCase
{
    private StreamInterface $stream;

    private ResultInterface $result;

    private CommandInterface $command;

    private S3ClientInterface $client;

    private AdapterInterface $adapter;

    protected function setUp(): void
    {
        $this->stream = $this->createMock(StreamInterface::class);

        $this->result = $this->createMock(ResultInterface::class);

        $this->command = $this->createMock(CommandInterface::class);

        $this->client = $this->createMock(S3ClientInterface::class);
        $this->client->method('getCommand')->willReturn($this->command);
        $this->client->method('execute')->willReturn($this->result);

        $this->adapter = new S3Adapter($this->client, 'test-bucket');
    }

    public function testItHasTheRightInterfaces(): void
    {
        $this->assertInstanceOf(AdapterInterface::class, $this->adapter);
        $this->assertInstanceOf(CopyAwareInterface::class, $this->adapter);
        $this->assertInstanceOf(DirectoryAwareInterface::class, $this->adapter);
        $this->assertInstanceOf(MoveAwareInterface::class, $this->adapter);
    }

    public function testItWillUploadFile(): void
    {
        $this->client->expects($this->once())->method('upload');

        $this->adapter->add('/path/to/file.ext', '');
    }

    public function testItCanGetFileContents(): void
    {
        $this->stream->method('getContents')->willReturn('file contents');
        $this->result->method('get')->with('Body')->willReturn($this->stream);

        $this->assertSame('file contents', $this->adapter->get('/path/to/file.ext'));
    }

    public function testItCanRemoveFile(): void
    {
        $this->client->expects($this->once())->method('getCommand')->with('DeleteObject')->willReturn($this->command);

        $this->adapter->remove('/path/to/file.ext');
    }

    public function testItHasFile(): void
    {
        $this->client->expects($this->once())->method('doesObjectExistV2')->willReturn(true);

        $this->assertTrue($this->adapter->has('/path/to/file.ext'));
    }

    public function testItHasDirectory(): void
    {
        $this->client->expects($this->once())->method('doesObjectExistV2')->willReturn(false);
        $this->result->method('hasKey')->willReturn(true);

        $this->assertTrue($this->adapter->has('/path/to'));
    }

    public function testIsFile(): void
    {
        $this->client->expects($this->once())->method('doesObjectExistV2')->willReturn(true);

        $this->assertTrue($this->adapter->isFile('/path/to/file.ext'));
    }

    public function testItCanCopyFile(): void
    {
        $this->client->expects($this->once())->method('copy');

        $this->adapter->copy('/path/to/source.ext', '/path/to/destination.ext');
    }

    public function testIsDirectory(): void
    {
        $this->result->method('hasKey')->willReturn(true);

        $this->assertTrue($this->adapter->isDirectory('/path/to'));
    }

    public function testItCanMakeDirectory(): void
    {
        $this->client->expects($this->once())->method('upload');

        $this->adapter->makeDirectory('/path/to');
    }

    public function testItCanRemoveDirectory(): void
    {
        $this->client->expects($this->once())->method('deleteMatchingObjects');

        $this->adapter->removeDirectory('/path/to');
    }

    public function testItCanMoveFile(): void
    {
        $this->client->expects($this->once())->method('copy');
        $this->client->expects($this->once())->method('getCommand')->with('DeleteObject')->willReturn($this->command);

        $this->adapter->move('/path/to/source.ext', '/path/to/destination.ext');
    }

    public function testItCanReturnContentType(): void
    {
        $this->result
             ->method('get')
             ->with('ContentType')
             ->willReturn('text/plain');

        $this->assertSame('text/plain', $this->adapter->mimeType('/path/to/source.ext'));
    }
}
