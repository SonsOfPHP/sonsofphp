<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing\Tests\Aggregate;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Symfony\EventSourcing\Aggregate\AggregateIdNormalizer;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateId;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Bridge\Symfony\EventSourcing\Aggregate\AggregateIdNormalizer
 */
final class AggregateIdNormalizerTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheRightInterface(): void
    {
        $normalizer = new AggregateIdNormalizer();

        $this->assertInstanceOf(NormalizerInterface::class, $normalizer); // @phpstan-ignore-line
        $this->assertInstanceOf(DenormalizerInterface::class, $normalizer); // @phpstan-ignore-line
    }

    /**
     * @covers ::supportsNormalization
     * @covers ::normalize
     */
    public function testItWillNormalize(): void
    {
        $normalizer = new AggregateIdNormalizer();

        $id = new AggregateId('aggregate-id');

        $this->assertTrue($normalizer->supportsNormalization($id));
        $this->assertSame('aggregate-id', $normalizer->normalize($id));
    }

    /**
     * @dataProvider providerForSupportsDenormalization
     *
     * @covers ::supportsDenormalization
     */
    public function testSupportsDenormalize(
        bool $expected,
        $data,
        string $type,
        string $format = null
    ): void {
        $normalizer = new AggregateIdNormalizer();

        $this->assertSame($expected, $normalizer->supportsDenormalization($data, $type, $format));
    }

    public function providerForSupportsDenormalization(): iterable
    {
        yield [true, 'aggregate-id', AggregateId::class];
        yield [true, 'aggregate-id', AggregateIdInterface::class];
        yield [false, 'aggregate-id', 'stdClass'];
    }

    /**
     * @dataProvider providerForDenormalize
     *
     * @covers ::denormalize
     */
    public function testDenormalize(
        $data,
        string $type,
        string $format = null,
        array $context = []
    ): void {
        $normalizer = new AggregateIdNormalizer();

        $output = $normalizer->denormalize($data, $type, $format, $context);

        $this->assertInstanceOf(AggregateIdInterface::class, $output);
        $this->assertSame($data, $output->toString());
    }

    public function providerForDenormalize(): iterable
    {
        yield ['aggregate-id', AggregateId::class];
        yield ['aggregate-id', AggregateIdInterface::class];
    }
}
