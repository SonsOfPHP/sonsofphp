<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpClient\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Client\ClientInterface;
use SonsOfPHP\Component\HttpClient\Client;
use SonsOfPHP\Component\HttpClient\Test\NullHandler;
use SonsOfPHP\Component\HttpClient\HandlerStack;

/**
 * @coversDefaultClass \SonsOfPHP\Component\HttpClient\Client
 *
 * @internal
 */
final class ClientTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(ClientInterface::class, new Client());
    }

    /**
     * @covers ::sendRequest
     */
    public function testSendRequestWorksAsExpected(): void
    {
        $client = new Client(new NullHandler());
        $this->assertInstanceOf(ResponseInterface::class, $client->sendRequest($this->createMock(RequestInterface::class)));
    }

    /**
     * @covers ::head
     */
    public function testHeadWorksAsExpected(): void
    {
        $client = new Client(new NullHandler());
        $this->assertInstanceOf(ResponseInterface::class, $client->head('https://docs.sonsofphp.com'));
    }

    /**
     * @covers ::get
     */
    public function testGetWorksAsExpected(): void
    {
        $client = new Client(new NullHandler());
        $this->assertInstanceOf(ResponseInterface::class, $client->get('https://docs.sonsofphp.com'));
    }

    /**
     * @covers ::post
     */
    public function testPostWorksAsExpected(): void
    {
        $client = new Client(new NullHandler());
        $this->assertInstanceOf(ResponseInterface::class, $client->post('https://docs.sonsofphp.com'));
    }

    /**
     * @covers ::put
     */
    public function testPutWorksAsExpected(): void
    {
        $client = new Client(new NullHandler());
        $this->assertInstanceOf(ResponseInterface::class, $client->put('https://docs.sonsofphp.com'));
    }

    /**
     * @covers ::patch
     */
    public function testPatchWorksAsExpected(): void
    {
        $client = new Client(new NullHandler());
        $this->assertInstanceOf(ResponseInterface::class, $client->patch('https://docs.sonsofphp.com'));
    }

    /**
     * @covers ::delete
     */
    public function testDeleteWorksAsExpected(): void
    {
        $client = new Client(new NullHandler());
        $this->assertInstanceOf(ResponseInterface::class, $client->delete('https://docs.sonsofphp.com'));
    }

    /**
     * @covers ::purge
     */
    public function testPurgeWorksAsExpected(): void
    {
        $client = new Client(new NullHandler());
        $this->assertInstanceOf(ResponseInterface::class, $client->purge('https://docs.sonsofphp.com'));
    }

    /**
     * @covers ::options
     */
    public function testOptionsWorksAsExpected(): void
    {
        $client = new Client(new NullHandler());
        $this->assertInstanceOf(ResponseInterface::class, $client->options('https://docs.sonsofphp.com'));
    }

    /**
     * @covers ::trace
     */
    public function testTraceWorksAsExpected(): void
    {
        $client = new Client(new NullHandler());
        $this->assertInstanceOf(ResponseInterface::class, $client->trace('https://docs.sonsofphp.com'));
    }

    /**
     * @covers ::connect
     */
    public function testConnectWorksAsExpected(): void
    {
        $client = new Client(new NullHandler());
        $this->assertInstanceOf(ResponseInterface::class, $client->connect('https://docs.sonsofphp.com'));
    }
}
