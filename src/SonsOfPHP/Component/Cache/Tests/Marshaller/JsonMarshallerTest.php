<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Tests\Marshaller;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Cache\Marshaller\JsonMarshaller;
use SonsOfPHP\Component\Cache\Marshaller\MarshallerInterface;

#[CoversClass(JsonMarshaller::class)]
final class JsonMarshallerTest extends TestCase
{
    private JsonMarshaller $marshaller;

    protected function setUp(): void
    {
        $this->marshaller = new JsonMarshaller();
    }

    public function testItHasTheCorrectInterface(): void
    {
        $this->assertInstanceOf(MarshallerInterface::class, $this->marshaller);
    }

    public function testItCanMarshallAndUnmarshallValues(): void
    {
        $value = 'testing';

        $this->assertSame($value, $this->marshaller->unmarshall($this->marshaller->marshall($value)));
    }
}
