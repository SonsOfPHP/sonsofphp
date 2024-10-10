<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventDispatcher\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\StoppableEventInterface;
use SonsOfPHP\Component\EventDispatcher\AbstractStoppableEvent;

#[CoversClass(AbstractStoppableEvent::class)]
#[UsesClass(AbstractStoppableEvent::class)]
#[CoversNothing]
final class AbstractStoppableEventTest extends TestCase
{
    #[CoversNothing]
    public function testItHasTheCorrectInterface(): void
    {
        $event = new class extends AbstractStoppableEvent {};

        $this->assertInstanceOf(StoppableEventInterface::class, $event);
    }

    public function testItCanStopPropagation(): void
    {
        $event = new class extends AbstractStoppableEvent {};
        $this->assertFalse($event->isPropagationStopped());

        $event->stopPropagation();
        $this->assertTrue($event->isPropagationStopped());
    }
}
