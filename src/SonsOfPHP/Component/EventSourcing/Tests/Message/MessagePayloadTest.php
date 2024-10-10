<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Message;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Message\MessagePayload;

/**
 * @internal
 */
#[CoversClass(MessagePayload::class)]
#[CoversNothing]
final class MessagePayloadTest extends TestCase
{
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
