<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing\Tests\Message;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Symfony\EventSourcing\Message\MessageNormalizer;
use SonsOfPHP\Component\EventSourcing\Message\AbstractMessage;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Bridge\Symfony\EventSourcing\Message\MessageNormalizer
 *
 * @uses \SonsOfPHP\Component\EventSourcing\Message\AbstractMessage
 * @uses \SonsOfPHP\Component\EventSourcing\Message\MessageMetadata
 * @uses \SonsOfPHP\Component\EventSourcing\Message\MessagePayload
 */
final class MessageNormalizerTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheRightInterface(): void
    {
        $normalizer = new MessageNormalizer();

        $this->assertInstanceOf(DenormalizerInterface::class, $normalizer);
        $this->assertInstanceOf(NormalizerInterface::class, $normalizer);
    }

    /**
     * @covers ::normalize
     * @covers ::supportsNormalization
     */
    public function testItWillNormalizeMessage(): void
    {
        $normalizer = new MessageNormalizer();

        $message = new class () extends AbstractMessage {};

        $this->assertTrue($normalizer->supportsNormalization($message));

        $output = $normalizer->normalize($message);

        $this->assertArrayHasKey('payload', $output);
        $this->assertArrayHasKey('metadata', $output);
    }
}
