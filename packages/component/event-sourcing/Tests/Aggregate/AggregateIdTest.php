<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Aggregate;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateId;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use PHPUnit\Framework\TestCase;

final class AggregateIdTest extends TestCase
{
    public function testItHasTheRightInterface(): void
    {
        $id = AggregateId::fromString('123');
        $this->assertInstanceOf(AggregateIdInterface::class, $id);

        $id = new AggregateId('123');
        $this->assertInstanceOf(AggregateIdInterface::class, $id);
    }

    public function testToString(): void
    {
        $id = AggregateId::fromString('123');

        $this->assertSame('123', $id->toString());
        $this->assertSame('123', (string) $id);
    }

    public function testEquals(): void
    {
        $idOne   = AggregateId::fromString('1ecb77a6-4b15-6a2e-a38c-3758fccf8ba6');
        $idTwo   = AggregateId::fromString('1ecb77a6-4b15-6a2e-a38c-3758fccf8ba6');
        $idThree = AggregateId::fromString('1ecb77ad-b0d9-6660-b450-311fcc0e1a8e');

        $this->assertTrue($idOne->equals($idOne));
        $this->assertTrue($idOne->equals($idTwo));
        $this->assertFalse($idOne->equals($idThree));
        $this->assertFalse($idThree->equals($idOne));
        $this->assertFalse($idThree->equals($idTwo));
    }
}
