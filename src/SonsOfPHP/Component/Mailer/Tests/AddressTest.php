<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Mailer\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Mailer\Address;
use SonsOfPHP\Contract\Mailer\AddressInterface;

#[CoversClass(Address::class)]
#[UsesClass(Address::class)]
final class AddressTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $address = new Address('joshua@sonsofphp.com');

        $this->assertInstanceOf(AddressInterface::class, $address);
    }

    public function testEmail(): void
    {
        $address = new Address('joshua@sonsofphp.com');

        $this->assertSame('joshua@sonsofphp.com', $address->getEmail());

        $this->assertSame($address, $address->withEmail('joshua@sonsofphp.com'));
        $this->assertNotSame($address, $address->withEmail('joshua.estes@sonsofphp.com'));
    }

    public function testName(): void
    {
        $address = new Address('joshua@sonsofphp.com');

        $this->assertNull($address->getName());

        $this->assertNotSame($address, $address->withName('Joshua'));

        $address = $address->withName('Joshua');
        $this->assertSame('Joshua', $address->getName());
    }

    public function testToStringMagicMethod(): void
    {
        $this->assertSame('joshua@sonsofphp.com', (string) new Address('joshua@sonsofphp.com'));
        $this->assertSame('Joshua Estes <joshua@sonsofphp.com>', (string) new Address('joshua@sonsofphp.com', 'Joshua Estes'));
    }

    public function testFrom(): void
    {
        // Would be best to use a data provider here
        $address = Address::from('joshua@sonsofphp.com');

        $this->assertSame('joshua@sonsofphp.com', $address->getEmail());
    }
}
