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
     * @covers ::supportsDenormalization
     * @covers ::denormalize
     */
    public function testItWillDenormalize(): void
    {
        $normalizer = new AggregateVersionNormalizer();

        $data = 2131;
        $type = AggregateVersion::class;

        $this->assertTrue($normalizer->supportsDenormalization($data, $type));

        $id = $normalizer->denormalize($data, $type);

        $this->assertInstanceOf(AggregateVersionInterface::class, $id);
        $this->assertSame($data, $id->toInt());
    }
}
