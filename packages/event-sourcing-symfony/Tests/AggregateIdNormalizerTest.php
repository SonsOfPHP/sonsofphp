<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Symfony\EventSourcing\AggregateIdNormalizer;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateId;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Bridge\Symfony\EventSourcing\AggregateIdNormalizer
 */
final class AggregateIdNormalizerTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheRightInterface(): void
    {
        $normalizer = new AggregateIdNormalizer();

        $this->assertInstanceOf(NormalizerInterface::class, $normalizer);
        $this->assertInstanceOf(DenormalizerInterface::class, $normalizer);
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
     * @covers ::supportsDenormalization
     * @covers ::denormalize
     */
    public function testItWillDenormalize(): void
    {
        $normalizer = new AggregateIdNormalizer();

        $data = 'aggregate-id';
        $type = AggregateId::class;

        $this->assertTrue($normalizer->supportsDenormalization($data, $type));

        $id = $normalizer->denormalize($data, $type);

        $this->assertInstanceOf(AggregateIdInterface::class, $id);
        $this->assertSame($data, $id->toString());
    }
}
