<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Mailer\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Mailer\Address;
use SonsOfPHP\Contract\Mailer\AddressInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Mailer\Address
 *
 * @uses \SonsOfPHP\Component\Mailer\Address
 */
final class AddressTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $address = new Address('joshua@sonsofphp.com');

        $this->assertInstanceOf(AddressInterface::class, $address);
    }

    /**
     * @covers ::getEmail
     * @covers ::withEmail
     */
    public function testEmail(): void
    {
        $address = new Address('joshua@sonsofphp.com');

        $this->assertSame('joshua@sonsofphp.com', $address->getEmail());

        $this->assertSame($address, $address->withEmail('joshua@sonsofphp.com'));
        $this->assertNotSame($address, $address->withEmail('joshua.estes@sonsofphp.com'));
    }

    /**
     * @covers ::getName
     * @covers ::withName
     */
    public function testName(): void
    {
        $address = new Address('joshua@sonsofphp.com');

        $this->assertNull($address->getName());

        $this->assertNotSame($address, $address->withName('Joshua'));

        $address = $address->withName('Joshua');
        $this->assertSame('Joshua', $address->getName());
    }

    /**
     * @covers ::__toString
     */
    public function testToStringMagicMethod(): void
    {
        $this->assertSame('joshua@sonsofphp.com', (string) new Address('joshua@sonsofphp.com'));
        $this->assertSame('Joshua Estes <joshua@sonsofphp.com>', (string) new Address('joshua@sonsofphp.com', 'Joshua Estes'));
    }

    /**
     * @covers ::from
     */
    public function testFrom(): void
    {
        // Would be best to use a data provider here
        $address = Address::from('joshua@sonsofphp.com');

        $this->assertSame('joshua@sonsofphp.com', $address->getEmail());
    }
}
