<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Message;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;
use SonsOfPHP\Component\EventSourcing\Message\MessagePayload;

/**
 * @coversDefaultClass \SonsOfPHP\Component\EventSourcing\Message\MessagePayload
 */
final class MessagePayloadTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::count
     * @covers ::with
     * @covers ::get
     * @covers ::has
     * @covers ::getIterator
     */
    public function testAllTheThings(): void
    {
        $payload = new MessagePayload([
            'account_id' => '1234',
            'user_id'    => '5678',
            'key'        => 'value',
        ]);
        $this->assertCount(3, $payload);
        $this->assertTrue($payload->has('account_id'));
        $this->assertSame('1234', $payload->get('account_id'));

        $another = $payload->with('your_mom', 'has the clap');
        $this->assertNull($payload->get('you_mom'));

        $this->assertNotSame($payload, $another);
        $this->assertCount(4, $another);
    }
}
