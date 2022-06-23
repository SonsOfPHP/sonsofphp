<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Symfony\EventSourcing\AggregateVersionNormalizer;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Bridge\Symfony\EventSourcing\AggregateVersionNormalizer
 */
final class AggregateVersionNormalizerTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheRightInterface(): void
    {
        $normalizer = new AggregateVersionNormalizer();

        $this->assertInstanceOf(NormalizerInterface::class, $normalizer);
        $this->assertInstanceOf(DenormalizerInterface::class, $normalizer);
    }

    /**
     * @covers ::supportsNormalization
     * @covers ::normalize
     */
    public function testItWillNormalize(): void
    {
        $normalizer = new AggregateVersionNormalizer();

        $id = new AggregateVersion(2131);

        $this->assertTrue($normalizer->supportsNormalization($id));
        $this->assertSame(2131, $normalizer->normalize($id));
    }

    /**
     * @dataProvider providerForSupportsDenormalization
     *
     * @covers ::supportsDenormalization
     */
    public function testItSupportsDenormalize(
        bool $expected,
        $data,
        string $type,
        string $format = null
    ): void {
        $normalizer = new AggregateVersionNormalizer();

        $this->assertSame($expected, $normalizer->supportsDenormalization($data, $type, $format));
    }

    public function providerForSupportsDenormalization(): iterable
    {
        yield [true, 2131, AggregateVersion::class];
        yield [true, 2131, AggregateVersionInterface::class];
        yield [false, 2131, 'stdClass'];
    }

    /**
     * @dataProvider providerForDenormalize
     *
     * @covers ::denormalize
     */
    public function testItWillDenormalize(
        $data,
        string $type,
        string $format = null,
        array $context = []
    ): void
    {
        $normalizer = new AggregateVersionNormalizer();

        $output = $normalizer->denormalize($data, $type, $format, $context);

        $this->assertInstanceOf(AggregateVersionInterface::class, $output);
        $this->assertSame($data, $output->toInt());
    }

    public function providerForDenormalize(): iterable
    {
        yield [2131, AggregateVersion::class];
        yield [2131, AggregateVersionInterface::class];
    }
}
